<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_reports', function (Blueprint $table) {
            $table->date('date')->comment('日期');
            $table->string('gameno',20);
            $table->integer('user_count')->unsigned()->comment('不重複帳號數');
            $table->integer('bet_count_avg')->unsigned()->comment('平均投注次數');
            $table->integer('total_bet')->unsigned()->nullable()->comment('總投注額');
            $table->float('total_bet_per')->comment('總投注額占比');
            $table->integer('total_bonus')->unsigned()->nullable()->comment('中彩金額');
            $table->float('total_bonus_per')->comment('中彩金額占比');
            $table->integer('netWin')->unsigned()->comment('NetWin值');
            $table->float('expectation')->comment('期望值');
            $table->integer('bet_count')->unsigned()->comment('投注次數');
            $table->integer('bonus_count')->unsigned()->comment('中彩金次數');
            $table->integer('total_bet_avg')->unsigned()->comment('平均投注額');
            $table->integer('total_bonus_avg')->unsigned()->comment('平均彩金額');
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
        Schema::drop('game_reports');
    }
}
