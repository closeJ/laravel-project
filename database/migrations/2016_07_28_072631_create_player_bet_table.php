<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerBetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_bets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->index();
            $table->string('platform')->index();
            $table->string('game_name');
            $table->integer('bet_amount');
            $table->integer('win_money');
            $table->timestamps();
            $table->foreign('username')->references('username')
            ->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('player_bets');
    }
}
