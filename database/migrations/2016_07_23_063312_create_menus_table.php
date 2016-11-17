<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('parent_id')->default(0);
            $table->string('name')->charset('utf8')->collate('utf8-general-ci');
            $table->string('controlName', 50);
            $table->string('methodName', 50);
            $table->string('routeName', 50);
            $table->tinyInteger('is_show')->default(1);
            $table->tinyInteger('is_sub')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menus');
    }
}
