<?php

use Illuminate\Database\Seeder;
use App\Model\Menu;
use Carbon\Carbon;
class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('menus')->insert([
    		'name' => '管理員管理',
    		'parent_id' => '0',
    		'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
    	]);
    	DB::table('menus')->insert([
    		'name' => '管理員列表',
    		'parent_id' => '1',
    		'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
    	]);
    	DB::table('menus')->insert([
    		'name' => '權限列表',
    		'parent_id' => '1',
    		'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
    	]);
    	DB::table('menus')->insert([
    		'name' => '用戶管理',
    		'parent_id' => '0',
    		'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
    	]);
    	DB::table('menus')->insert([
    		'name' => '代理管理',
    		'parent_id' => '0',
    		'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
    	]);
    	DB::table('menus')->insert([
    		'name' => '營運報表',
    		'parent_id' => '0',
    		'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
    	]);
    	DB::table('menus')->insert([
    		'name' => '遊戲監控',
    		'parent_id' => '0',
    		'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
    	]);
    }
}
