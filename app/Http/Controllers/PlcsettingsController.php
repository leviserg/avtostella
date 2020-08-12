<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plcsettings;
use Illuminate\Support\Facades\Auth;

class PlcsettingsController extends Controller
{
    public function index()
    {
        /*
        $user = Auth::user();
        if(!is_null($user)){
            $settings = Plcsettings::all();
            return view('settings', compact('settings'));
        }
        else{
            return view('auth.login');
        }
        */
    }

    public function getselectedsetting($id)
    {
        $settingdata = Plcsettings::where('id','=',$id)->first();
        return json_encode($settingdata);
    }

    public function storesetting(Request $request){
        Plcsettings::where('id', '=', $request->id)
            ->update([
                'setting_plc1'     => $request->sett_plc1,
                'setting_plc2'     => $request->sett_plc2,
                'setting_plc3'     => $request->sett_plc3,
                'setting_plc4'     => $request->sett_plc4,
            ]);
        return redirect('/settings');
    }
}
