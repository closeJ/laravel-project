<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_counts', function (Blueprint $table) {
            $table->date('date')->comment('日期');
            $table->string('gameno',20);
            $table->integer('user_count')->unsigned()->comment('不重複帳號數');
            $table->float('user_count_per')->comment('帳號數占比');
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
        Schema::drop('player_counts');
    }
}
