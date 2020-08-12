<?php

use Illuminate\Database\Seeder;

class AlarmcategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<3;$i++){
            $svar = '';
            switch ($i) {
                case 0:
                    $svar ='Авария';
                    break;
                case 1:
                    $svar ='Предупрежд';
                    break;
                default:
                    $svar ='Событие';
            }
            DB::table('alarmcategories')->insert([
                'category' => $svar
            ]);
        }
    }
}
