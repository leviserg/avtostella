<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AlarmTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $maxrecords = 40; // max 60
        for($i=0;$i<$maxrecords;$i++){
            $descrpition = 'Аварийное сообщение '.($i+1);
            $categ = $i%2+1; // 1-alarm / 2-warning / 3-event - join on alarmcategory table
            $status = $i%2 + 1; // 1-active unack / 2-act ack / 3-nonact unack / 4-nonact ack join on alarmstatus table
            //$recdate = Carbon::create(2019, 09, 27, 14, $i, 0, 'GMT')->format('Y-m-d H:i:s');
            $appeared = Carbon::create(2019, 9, 27, 14, $i, $i%8, 'GMT')->format('Y-m-d H:i:s');//Carbon::now()->format('Y-m-d H:i:s');
            $acknowledged = null;
            $acknowledgedby = null;
            if($status==2){
                $acknowledged = Carbon::now()->format('Y-m-d H:i:s');
                $acknowledgedby = 'user';
            }
            DB::table('alarms')->insert([
                'descr' =>  $descrpition,
                'categ' => $categ,
                'status' => $status,
                'appeared' => $appeared,
                'acknowledged' => $acknowledged,
                'acknowledgedby' => $acknowledgedby
            ]);
        }
        
    }
}
