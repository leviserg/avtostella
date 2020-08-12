<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Axesconfig;
use App\Axesconfig56;

class HistdataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $points = 20;
        $sensors = Axesconfig::count('sensorid');
        for($i=0;$i<$sensors;$i++){
            for($j = 0; $j < $points; $j++){
                $month = 2;
                $day = $j + 1;
                DB::table('hist_data')->insert([
                    'sensorid' => ($i + 1),
                    'convid' => 34,
                    'recdate' => Carbon::create(2020, $month, $day, random_int(0,23), random_int(0,59), random_int(0,59), 'GMT')->format('Y-m-d H:i:s'),
                    'value' => mt_rand(100, 1000)/100,
                    'validity' => 0,
                ]);
            }
        }

        $points = 20;
        $sensors = Axesconfig56::count('sensorid');
        for($i=0;$i<$sensors;$i++){
            for($j = 0; $j < $points; $j++){
                $month = 5;
                $day = $j + 1;
                DB::table('hist_data')->insert([
                    'sensorid' => ($i + 1),
                    'convid' => 56,
                    'recdate' => Carbon::create(2020, $month, $day, random_int(0,23), random_int(0,59), random_int(0,59), 'GMT')->format('Y-m-d H:i:s'),
                    'value' => mt_rand(5000, 9999)/100, //random_int(100, 9999)/100
                    'validity' => 0,
                ]);
            }
        }
    }
}
