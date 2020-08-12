<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\User::class, 1)->create();
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin'.'@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'), // password
            'level' => 2,
            'remember_token' => Str::random(10),
        ]);
        DB::table('users')->insert([
            'name' => 'user',
            'email' => 'user'.'@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('user'), // password
            'remember_token' => Str::random(10),
        ]);
    }
}
