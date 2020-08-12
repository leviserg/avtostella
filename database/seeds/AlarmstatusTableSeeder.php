<?php

use Illuminate\Database\Seeder;

class AlarmstatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        for($i=0;$i<4;$i++){
            $svar = '';
            switch ($i) {
                case 0:
                    $svar ='Неквитиров';
                    break;
                case 1:
                    $svar ='Квитирован';
                    break;
                case 2:
                    $svar ='НеАкт.Неквит';
                    break;
                default:
                    $svar ='НеАкт.Квитир';
            }
            */
            for($i=0;$i<2;$i++){
                $svar = '';
                switch ($i) {
                    case 1:
                        $svar ='Квитирован';
                        break;
                    default:
                        $svar ='Неквитиров';
                }
            DB::table('alarmstatuses')->insert([
                'status' => $svar
            ]);
        }
    }
}
