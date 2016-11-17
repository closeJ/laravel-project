<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'name' => 'Admin' ,
        	'username' => 'admin001',
            'email' => 'ad@rrr.com',
        	'password' => bcrypt(1234568787),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);
        DB::table('users')->insert([
        	'name' => 'six' ,
        	'username' => 'rpg6666',
        	'email' => 'six@gmail.com',
        	'password' => bcrypt('kk123123'),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);
    }
}
