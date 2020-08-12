<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Axesconfig;
use App\Axesconfig56;
use App\HistData;
use App\Auxiliar;
use DB;
use App\Stella;
use mysqli;
use App\Analog;

class MainController extends Controller
{
    public function trends(){
        /*
        $user = Auth::user();
        if(!is_null($user)){
            $sectionlist = Axesconfig56::where('conveyor','=','51')->distinct()->get(['section']);
            $firstsection = $sectionlist->min();
            $match = ['conveyor' => '51','section' => $firstsection->section];
            $axeslist = Axesconfig56::where($match)->get(['axeid','sensorid']);
            return view('trends', compact('sectionlist','firstsection','axeslist'));
        }
        else{
            return view('auth.login');
        }
        */
        $sectionlist = Axesconfig56::where('conveyor','=','51')->distinct()->get(['section']);
        $firstsection = $sectionlist->min();
        $match = ['conveyor' => '51','section' => $firstsection->section];
        $axeslist = Axesconfig56::where($match)->get(['axeid','sensorid']);
        return view('trends', compact('sectionlist','firstsection','axeslist'));
    }

    public function trendsgetsections($conv = 56){
        $model = 'App\Axesconfig';
        if($conv > 50)
            $model .= '56';
        $sectionlist = $model::where('conveyor','=',$conv)->distinct()->get(['section']);
        $firstsection = $sectionlist->min();
        $match = ['conveyor' => $conv,'section' => $firstsection->section];
        $firstaxeslist = $model::where($match)->get(['axeid','sensorid']);
        return json_encode([$sectionlist,$firstaxeslist]);
    }

    public function trendsgetaxes($conv = 56, $section){
        $model = 'App\Axesconfig';
        if($conv > 50)
            $model .= '56';
        $match = ['conveyor' => $conv, 'section' => $section];
        $axeslist = $model::where($match)->get(['axeid','sensorid']);
        return json_encode($axeslist);
    }

    public function trendsgetsensor($conv = 56, $id){
        $convid = 56;
        if($conv < 50)
            $convid = 34;
        $match = [
            'convid' => $convid,
            'sensorid' => $id
        ];
        $sensdata = HistData::where($match)->get(['recdate','value']);
        return json_encode($sensdata);
    }

    public function indexconv($convid = 56){
        $model = 'App\Axesconfig';
        if($convid > 50)
            $model .= '56';
        $conveyors = $model::distinct()->get('conveyor');
        $sections = $model::count('section');
        $axes = $model::count('axeid');
        $sensors = $model::count('sensorid');
        $lastdate = HistData::where('convid','=',$convid)->max('recdate');
        $newDate = date("d.m.Y H:i:s", strtotime($lastdate));
        $axesdata = [
            'axes' => $axes,
            'sections' => $sections,
            'sensors' =>  $sensors,
            'conveyors' => $conveyors,
            'lastdate' => $newDate
        ];
        return view('main', compact('axesdata'));
    }

    public function gotoconv($convid = 56, $selectedate){
        $ret = [];
        $model = 'App\Axesconfig';
        $table = 'axesconfigs';
        if($convid > 50){
            $model .= '56';
            $table = 'axesconfig56s';
        }
        $sensors = $model::count('sensorid');
        for($i = 1; $i<= $sensors; $i++){
            $ret[$i] = HistData::join($table, 'hist_data.sensorid','=', $table.'.sensorid')
                ->select([
                    'hist_data.sensorid as sensorid',
                    $table.'.axeid as axeid',
                    $table.'.section as msection',
                    $table.'.conveyor as conveyor',
                    $table.'.mill as mill',
                    'hist_data.recdate as recdate',
                    'hist_data.value as value',
                    'hist_data.validity as validity',
                    DB::raw('HOUR(TIMEDIFF(`hist_data`.`recdate`, "'.$selectedate.'")) as diff')
                ])
                ->where('hist_data.sensorid',$i)
                ->where('hist_data.convid','=',$convid)
                ->where('hist_data.recdate','<=',$selectedate)
                ->get()->last();
                //->orderBy('hist_data.recdate','desc')
                //->first();
        }
        return $ret;
    }

    public function getstelladata($convid = 56, $selectedate){
        $ret = [];
        $ai = [];
        $conveyors = [31,32,41,42];
        if($convid > 50){
            $conveyors = [51,52,61,62];
        }
        for($i = 0; $i<count($conveyors); $i++){
            $ret[$i] = Stella::join('analogs', 'stellas.conveyor','=', 'analogs.conveyor')
                ->select([
                    'stellas.status as status',
                    'stellas.conveyor as conveyor',
                    'stellas.position as position',
                    'stellas.axeid as axeid',
                    'stellas.created_at as created_at', 
		    'analogs.recdate as recdate',
                    'analogs.ai as ai',
                    'analogs.level as level',                                                   
                ])
                ->where('stellas.conveyor','=',$conveyors[$i])
                ->where('stellas.created_at','<=',$selectedate)
                ->latest()
                ->first();
                //->orderBy('stellas.created_at','desc')
                //->first();
        }
        return $ret;
    }

    public function getfeed($convid = 56, $selectedate){

        $secure = Auxiliar::andbconn();
        $dbname = "avtostella";
        $tblname = "tblCurrState";
        $dbdriver = "mysql"; 
        $user = $secure['user'];
        $pwd = $secure['pwd'];
        $host = "localhost";
        $colname = "CURR_STATE";

        try{
            $mysqli = new mysqli($host,$user,$pwd,$dbname);
            $sql = "select `".$colname."` from `".$tblname."` where (`NAME` like 'KL_KNV_O5%' or `NAME` LIKE 'KL_KNV_O6%' or `NAME` = 'W_7P1') order by `NAME` asc";
            if ($mysqli->connect_errno) {
                return die("Error db connection: " . $mysqli->connect_error);
            }
            else{

                $res = $mysqli->query($sql);
                $ret = [];
                while($data = $res->fetch_array()){
                    $ret[] = $data[$colname];
                }
                $res->close();
                $mysqli->close();
                $retdata = [];
                $operated = 0;
                $weight = $ret[count($ret)-1];                
                for($i=0; $i < count($ret)-1; $i++){
                    if($ret[$i] == 1){
                        $operated++;
                    }
                }
                for($i=0; $i < count($ret)-1; $i++){
                    if($ret[$i] == 1){
                        $retdata[$i] = [
                            "status" => $ret[$i],
                            "weight" => round($weight / $operated,1)
                        ];
                    }
                    else{
                        $retdata[$i] = [
                            "status" => $ret[$i],
                            "weight" => 0
                        ];                        
                    }
                }
                return json_encode($retdata);
            }
        } catch(mysqli_sql_exception $e){
            return die("Error db connection: ".$e->getMessage());
        }
    }

    public function forecast($convid = 56){

        $ret = [];
        $model = 'App\Axesconfig';
        $table = 'axesconfigs';
        if($convid > 50){
            $model .= '56';
            $table = 'axesconfig56s';
        }
        $sensors = $model::count('sensorid');
        for($i = 1; $i<= $sensors; $i++){
            $ret[$i] = HistData::join($table, 'hist_data.sensorid','=', $table.'.sensorid')
                ->select([
                    'hist_data.sensorid as sensorid',
                    $table.'.axeid as axeid',
                    $table.'.section as msection',
                    $table.'.conveyor as conveyor',
                    $table.'.mill as mill',                    
                    'hist_data.recdate as recdate',
                    'hist_data.value as value',
                    'hist_data.validity as validity',
                    DB::raw('HOUR(TIMEDIFF(`hist_data`.`recdate`, NOW())) as diff')
                ])
                ->where('hist_data.sensorid',$i)
                ->where('hist_data.convid','=',$convid)
                ->where('hist_data.recdate','<=','NOW()')
                ->orderBy('hist_data.recdate','desc')
                ->first();
        }

        $exret = $ret;
        
        $temp = json_decode($this->getweight($convid));

        for($i = 1; $i<= $sensors; $i++){
            $forevalue = 0;
            $fullweight = Auxiliar::getfullweight($ret[$i]);
            
            for($j=0; $j < count($temp); $j++){
                if($ret[$i]->mill == $temp[$j]->mill){
                    $forevalue = Auxiliar::getforevalue($fullweight, $temp[$j], $ret[$i]);
                    break;
                }
            }
            
            $exret[$i]->sensorid = $ret[$i]->sensorid;
            $exret[$i]->axeid = $ret[$i]->axeid;
            $exret[$i]->msection = $ret[$i]->msection;
            $exret[$i]->conveyor = $ret[$i]->conveyor;
            $exret[$i]->mill = $ret[$i]->mill;
            $exret[$i]->recdate =  Carbon::now()->format('Y-m-d H:i:s');
            $exret[$i]->value = $forevalue;
            $exret[$i]->diff = 0;
            $exret[$i]->validity = 0;
        }
        
        return $exret;
    }

    public function getweight($convid = 56){
        $model = 'App\Axesconfig';
        if($convid > 50)
            $model .= '56';
        $sensdata = $model::select('sensorid','mill')->get();
        $millweight = Auxiliar::getmillweight();

        for($i = 0; $i < count($millweight); $i++){
            $axespermill = 0;
            for($j = 0; $j< count($sensdata); $j++){
                if($sensdata[$j]->mill == $millweight[$i]['mill']){
                    $axespermill++;
                }
            }
            $millweight[$i] = [
                'mill' => $millweight[$i]['mill'],
                'weight' => $millweight[$i]['weight'],
                'convid' => Auxiliar::getconvfrommill($millweight[$i]['mill']),
                'axes' => $axespermill,
                'axweight' => round($millweight[$i]['weight']/$axespermill,2)
            ];
        }
        return json_encode($millweight);
    }

    // ================= not used ==================
    
    public function forecastconv($convid = 56, $days, $period){
        $curdate = date('Y-m-d H:i:s',time());
        $backdate = date('Y-m-d H:i:s',strtotime('-'.$days.' days',time()));
        $check2date = date('Y-m-d H:i:s',strtotime('-'.intval($period/2).' days',time()));
        $checkdate = date('Y-m-d H:i:s',strtotime('-'.$period.' days',time()));
        $foredate = date('Y-m-d H:i:s',strtotime('+'.$days.' days',time()));

        $curdata = $this->GetSensData($convid, $curdate);
        $backdata = $this->GetSensData($convid, $backdate);
        $checkdata = $this->GetSensData($convid, $checkdate);
        $check2data = $this->GetSensData($convid, $check2date);

        $retdata = [];
        for($i = 1; $i <= count($checkdata); $i++){
            $retdata[$i]['sensorid'] = $checkdata[$i]['sensorid'];
            $retdata[$i]['axeid'] = $checkdata[$i]['axeid'];
            $retdata[$i]['msection'] = $checkdata[$i]['msection'];
            $retdata[$i]['conveyor'] = $checkdata[$i]['conveyor'];
            $retdata[$i]['recdate'] = $foredate;
            $retdata[$i]['value'] = round(($curdata[$i]['value'] + $backdata[$i]['value'] + $checkdata[$i]['value'] + $check2data[$i]['value'])/4,2);
            $retdata[$i]['diff'] = -1;
        }

        return $retdata;
    }

    private function GetSensData($convid = 56, $date){
        $ret = [];
        $model = 'App\Axesconfig';
        $table = 'axesconfigs';
        if($convid > 50){
            $model .= '56';
            $table = 'axesconfig56s';
        }
        $sensors = $model::count('sensorid');

        for($i = 1; $i<= $sensors; $i++){
            $ret[$i] = HistData::join($table, 'hist_data.sensorid','=', $table.'.sensorid')
                ->select([
                    'hist_data.sensorid as sensorid',
                    $table.'.axeid as axeid',
                    $table.'.section as msection',
                    $table.'.conveyor as conveyor',
                    'hist_data.value as value'
                ])
                ->where('hist_data.sensorid',$i)
                ->where('hist_data.convid','=',$convid)
                ->where('hist_data.recdate','<=',$date)
                ->orderBy('hist_data.recdate','desc')
                ->first();
        }
        return $ret;
    }

    public function melogout(){
        //Auth::logout();
        //return view('auth.login');
    }

}
