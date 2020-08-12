<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Alarm;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AlarmController extends Controller
{

    public function alarms()
    {

        /*
        $user = Auth::user();
        if(!is_null($user)){
            return view('alarms');
        }
        else{
            return view('auth.login');
        }
        */
        
        return view('alarms');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getalarms()
    {
        $checkdate = Carbon::today()->subDays(365);
        $data = Alarm::join('alarmcategories', 'alarms.categ', '=', 'alarmcategories.id')
                ->join('alarmstatuses', 'alarms.status', '=', 'alarmstatuses.id')
                ->select([
                    'alarms.id as id',
                    'alarms.appeared as appeared',
                    'alarms.descr as descr',
                    'alarmcategories.category as categ',
                    'alarmstatuses.status as status',
                    'alarms.acknowledged as acknowledged',
                    'alarms.acknowledgedby as acknowledgedby'
                ])
                ->where('alarms.appeared', '>=', $checkdate)
                ->orderBy('alarms.id','desc')->get();
                //->orderBy('alarms.id','desc')->limit(2500)->get();

        return Datatables::of($data)->make(true);
    }

    public function acknowledge(Request $request)
    {
        $curuser = $_SERVER['REMOTE_ADDR']; //'unknown';//Auth::user()->name;
        //$cursts = Alarm::find($request->id)->status;
        Alarm::where('id', '=', $request->id)
            ->update(
                [
                    'status' => 2,
                    'acknowledged' => Carbon::now('Europe/Kiev')->format('Y-m-d H:i:s'),
                    'acknowledgedby' => $curuser
                ]
            );
        return null;
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getactunack()
    {
        //$act = Alarm::where('disappeared', '=', null)->count();
        $unack = Alarm::where('acknowledged', '=', null)->count();
        //return [$act, $unack];
        return $unack;
    }

}
