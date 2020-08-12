<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alarmstatus extends Model
{
    public function status(){
        return $this->hasOne('App\Alarm','status','id');
    }
}
