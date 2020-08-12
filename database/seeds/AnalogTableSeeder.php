<?php

use Illuminate\Database\Seeder;
use DB;

class AnalogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('analogs')->insert(
            [
                'id' => 1,
                'conveyor' => 51,
                'ai' => 15642,
                'level' => round(15642*100/27648,2)
            ],
            [
                'id' => 2,
                'conveyor' => 52,
                'ai' => 10243,
                'level' => round(10243*100/27648,2)
            ],
            [
                'id' => 2,
                'conveyor' => 61,
                'ai' => 7268,
                'level' => round(7268*100/27648,2)
            ],
            [
                'id' => 3,
                'conveyor' => 62,
                'ai' => 5478,
                'level' => round(5478*100/27648,2)
            ]           
        );  
    }
}