<?php

use Illuminate\Database\Seeder;

class PlcsettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = 12;
        for($i=0; $i<$params; $i++){
            DB::table('plcsettings')->insert([
                'name' => 'Параметр '.($i+1),
                'setting_plc1' => round(rand(1,100)*(($i+1)%6)*0.3364,2),
                'setting_plc2' => round(rand(2,100)*(($i+1)%4)*0.557,2),
                'setting_plc3' => round(rand(3,100)*(($i+1)%5)*0.278,2),
                'setting_plc4' => round(rand(4,100)*(($i+1)%7)*0.442,2),
            ]);
        }
    }
}
