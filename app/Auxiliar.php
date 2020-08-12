<?php

namespace App;
use mysqli;

class Auxiliar
{
    public static function andbconn(){
        return [
            'user' => "root",
            'pwd'  => ""
        ];
    }

    public static function rofdata($convid = 56){

        $ROF2remains = 18257;
        $ROF2total = 93596;
        $ROF3remains = 3827;
        $ROF3total = 34065;

        $model = 'App\Axesconfig56';

        $sensors = [
            '5A' => $model::where('conveyor','=', 51)->count('sensorid'),
            '6A' => $model::where('conveyor','=', 61)->count('sensorid'),
            '5B' => $model::where('conveyor','=', 52)->count('sensorid'),
            '6B' => $model::where('conveyor','=', 62)->count('sensorid'),
        ];

        $spec5A = round(($ROF2total - $ROF2remains) / (2 * $sensors['5A']),3);
        $spec6A = round(($ROF2total - $ROF2remains) / (2 * $sensors['6A']),3);  
        $spec5B = round(($ROF3total - $ROF3remains) / (2 * $sensors['5B']),3);
        $spec6B = round(($ROF3total - $ROF3remains) / (2 * $sensors['6B']),3);

        return [
            ['convid' => 51, 'spec' => $spec5A],
            ['convid' => 61, 'spec' => $spec6A],
            ['convid' => 52, 'spec' => $spec5B],
            ['convid' => 62, 'spec' => $spec6B]
        ];

    }

    public static function getconvfrommill($mill){
        if((($mill-3)%10 == 0 && $mill < 200) || $mill == 241){
            return 51;
        }
        elseif ((($mill-1)%10 == 0 && $mill < 200) || $mill == 243){
            return 61;
        }
        else{
            return 52;
        }
    }

    public static function getfullweight($sensor){
        $rofdata = self::rofdata();
        for($i = 0; $i < count($rofdata); $i++){
            if($sensor->conveyor == $rofdata[$i]['convid']){
                return $rofdata[$i]['spec'];
            }
        }
        return 0;
    }

    public static function getforevalue($fullweight, $objmill, $objsens){
        $actweightflow = $objmill->axweight;
        if($actweightflow < 0){
            $actweightflow = 0;
        }
        $forevalue = $objsens->value - ($actweightflow * $objsens->diff *100 / $fullweight);
        if($forevalue < 0)
             $forevalue = 0;
        return round($forevalue,2);
    }

    public static function getmillweight(){
        
        $secure = self::andbconn();
        $dbname = "avtostella"; // data has been selected from another database than applicatiob
        $tblname = "tblCurrState";
        $dbdriver = "mysql"; 
        $user = $secure['user'];
        $pwd = $secure['pwd'];
        $host = "localhost";
        $colname = "CURR_STATE";

        try{
            $mysqli = new mysqli($host,$user,$pwd,$dbname);
            $sql = "select `NAME`, `".$colname."` from `".$tblname."` where (`NAME` like 'R_MMC_%' and not `NAME` like 'R_MMC_20%') order by `NAME` asc";
            if ($mysqli->connect_errno) {
                return null;
            }
            else{
                $res = $mysqli->query($sql);
                $ret = [];
                while($data = $res->fetch_assoc()){

                    $temp = [
                        'mill'   =>  substr($data['NAME'], -3),
                        'weight' =>  $data[$colname]
                    ];

                    array_push($ret, $temp);
                }
                $res->close();
                $mysqli->close();
                return $ret;
            }
        } catch(mysqli_sql_exception $e){
            return null;
        }
    }

}