<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function events()
    {
        /*
        $user = Auth::user();
        if(!is_null($user)){
            return view('events');
        }
        else{
            return view('auth.login');
        }
        */
        return view('events');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getevents()
    {
        $checkdate = Carbon::today()->subDays(30);
        $data = Event::join('alarmcategories', 'events.categ', '=', 'alarmcategories.id')
                ->select([
                    'events.id as id',
                    'events.appeared as appeared',
                    'events.descr as descr',
                    'alarmcategories.category as categ'
                ])
                ->where('events.appeared', '>=', $checkdate)
                ->orderBy('events.id','desc')->get();

        return Datatables::of($data)->make(true);
    }
}
