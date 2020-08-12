<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StellaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $points = 20;
        for($i = 0; $i < $points; $i++){

            $conveyor = 51;
            $conf = [110,182];

            if($i%4 == 1){
                $conveyor = 52;
                $conf = [199,225];
            } elseif ($i%4 == 2){
                $conveyor = 61;
            }
            elseif ($i%4 == 3){
                $conveyor = 62;
                $conf = [199,225];
            }

            $axe = random_int($conf[0],$conf[1]);
            $position = round(($axe - $conf[0])*90/($conf[1]-$conf[0]));

            DB::table('stellas')->insert([
                'status' => ($i%4),
                'conveyor' => $conveyor,
                'position' => $position,
                'axeid' => $axe,
                'created_at' => Carbon::create(2020, 5, $i+1, random_int(0,23), random_int(0,59), random_int(0,59), 'GMT')->format('Y-m-d H:i:s'),
            ]);
        }

        for($i = 0; $i < $points; $i++){

            $conveyor = 31;
            $conf = [110,182];

            if($i%4 == 1){
                $conveyor = 32;
                $conf = [199,225];
            } elseif ($i%4 == 2){
                $conveyor = 41;
            }
            elseif ($i%4 == 3){
                $conveyor = 42;
                $conf = [199,225];
            }

            $axe = random_int($conf[0],$conf[1]);
            $position = round(($axe - $conf[0])*90/($conf[1]-$conf[0]));

            DB::table('stellas')->insert([
                'status' => ($i%4),
                'conveyor' => $conveyor,
                'position' => $position,
                'axeid' => $axe,
                'created_at' => Carbon::create(2020, 5, $i+1, random_int(0,23), random_int(0,59), random_int(0,59), 'GMT')->format('Y-m-d H:i:s'),
            ]);
        }

    }
}
