<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alarmcategory extends Model
{
    public function category(){
        return $this->hasOne('App\Alarm','categ','id');
    }

    public function eventcategory(){
        return $this->hasOne('App\Event','categ','id');
    }

}
