<?php

use Illuminate\Database\Seeder;

class Axesconfig56TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = 8;
        $axes = 9;
        $sensorNumber = 1;
        for($i=0;$i<2;$i++){
            ($i==0) ? $conv = 51 : $conv = 61;
            for($j=0;$j<$sections;$j++){
                $section = $j+1;
                for($k=0;$k<$axes;$k++){
                    $axe = $k + 1 + $j*$axes;
                    DB::table('axesconfig56s')->insert([
                        'sensorname' => $axe.'.'.$sensorNumber,
                        'axeid' => $axe + 109,
                        'section' => $section,
                        'conveyor' => $conv,
                    ]);
                    $sensorNumber++;
                }
            }
            DB::table('axesconfig56s')->insert([
                'sensorname' => $axe.'.'.$sensorNumber,
                'axeid' => $axe + 110,
                'section' => $section,
                'conveyor' => $conv,
            ]);
        }

        $next_axe = $sections*$axes;
        $next_axe = 198;
        $next_section = $sections;

        $smsections = 3;
        $smaxes = 9;
        $sensorNumber = 1;
        for($i=0;$i<2;$i++){
            ($i==0) ? $conv = 52 : $conv = 62;
            for($j = $next_section; $j < $next_section + $smsections; $j++){
                $section = $j + 1;
                for($k = $next_axe; $k < $next_axe + $smaxes; $k++){
                    $axe = $k + 1 + ($j - $next_section)*$smaxes;
                    DB::table('axesconfig56s')->insert([
                        'sensorname' => $axe.'.'.$sensorNumber,
                        'axeid' => $axe,
                        'section' => $section,
                        'conveyor' => $conv,
                    ]);
                    $sensorNumber++;
                }
            }
        }
    }
}
