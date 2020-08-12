<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    public function categ(){
        return $this->belongsTo('App\Alarmcategory','categ','id');
    }

    public function status(){
        return $this->belongsTo('App\Alarmstatus','status','id');
    }
}
