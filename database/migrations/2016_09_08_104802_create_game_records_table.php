<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->comment('遊戲類別');
            $table->string('username',32)->index()->comment('帳號');
            $table->string('nickname',32)->nullable()->comment('暱稱');
            $table->integer('net_win')->length(20)->unsigned()->comment('輸贏');
            $table->integer('income')->length(20)->unsigned()->comment('單日總投注額');
            $table->integer('income_count')->length(20)->comment('單日總投注次數');
            $table->float('expected_value')->comment('期望值');
            $table->date('date');
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
        Schema::drop('game_records');
    }
}
