<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(AlarmcategoryTableSeeder::class);
        $this->call(AlarmstatusTableSeeder::class);
        $this->call(AxesconfigTableSeeder::class);
        $this->call(Axesconfig56TableSeeder::class);
        $this->call(AlarmTableSeeder::class);
        $this->call(EventTableSeeder::class);
        $this->call(HistdataTableSeeder::class);
        $this->call(PlcsettingsTableSeeder::class);
        $this->call(StellaTableSeeder::class);
        $this->call(AnalogTableSeeder::class);
    }
}
