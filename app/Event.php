<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function categ(){
        return $this->belongsTo('App\Alarmcategory','categ','id');
    }
}
