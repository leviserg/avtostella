<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $maxrecords = 50;
        for($i=0;$i<$maxrecords;$i++){
            $categ = $i%3+1;
            switch ($categ) {
                case 1:
                    $descrpition = "Аварийное событие ".($i + 1);
                    break;
                case 2:
                    $descrpition = "Предупреждение Технолог ".($i + 1);
                    break;
                default :
                    $descrpition = "Информация. Оборудование вкл. ".($i + 1);
            }
            //$appeared = Carbon::now()->format('Y-m-d H:i:s');
            $appeared = $appeared = Carbon::create(2019, 9, 27, 13, $i, $i%8, 'GMT')->format('Y-m-d H:i:s');;
            DB::table('events')->insert([
                'descr' =>  $descrpition,
                'categ' => $categ,
                'appeared' => $appeared,
            ]);
        }
        
    }
}
