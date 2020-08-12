<?php

use Illuminate\Database\Seeder;

class AxesconfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $sections = 5;
        $axes = 8;
        $sensorNumber = 1;

        for($i=0;$i<2;$i++){
            ($i==0) ? $conv = 31 : $conv = 41;
            for($j=0;$j<$sections;$j++){
                $section = $j+1;
                for($k=0;$k<$axes;$k++){
                    $axe = $k + 1 + $j*$axes;
                    DB::table('axesconfigs')->insert([
                        'sensorname' => $axe.'.'.$sensorNumber,
                        'axeid' => $axe,
                        'section' => $section,
                        'conveyor' => $conv,
                    ]);
                    $sensorNumber++;
                }
            }
        }

        $next_axe = $sections*$axes;
        $next_section = $sections;

        $smsections = 4;
        $smaxes = 3;
        $sensorNumber = 1;

        for($i=0;$i<2;$i++){
            ($i==0) ? $conv = 32 : $conv = 42;
            for($j = $next_section; $j < $next_section + $smsections; $j++){
                $section = $j + 1;
                for($k = $next_axe; $k < $next_axe + $smaxes; $k++){
                    $axe = $k + 1 + ($j - $next_section)*$smaxes;
                    DB::table('axesconfigs')->insert([
                        'sensorname' => $axe.'.'.$sensorNumber,
                        'axeid' => $axe,
                        'section' => $section,
                        'conveyor' => $conv,
                    ]);
                    $sensorNumber++;
                }
            }
        }

        /*
        for($i=0;$i<4;$i++){
            switch ($i) {
                case 0:
                    $conv = 32;
                    $sections = 3;
                    $axes = 4;
                    break;
                case 1:
                    $conv = 42;
                    $sections = 3;
                    $axes = 4;
                    break;
                case 2:
                    $conv = 31;
                    $sections = 5;
                    $axes = 8;
                    break;
                default:
                    $conv = 41;
                    $sections = 5;
                    $axes = 8;
            }
            for($j=0;$j<$sections;$j++){
                $section = $j+1 + $i*$sections;
                for($k=0;$k<$axes;$k++){
                    $axe = $k + 1 + ($i*$sections + $j)*$axes;
                    DB::table('axesconfigs')->insert([
                        'axeid' => $axe,
                        'section' => $section,
                        'conveyor' => $conv,
                    ]);
                }
            }
        }
        */

    }
}
