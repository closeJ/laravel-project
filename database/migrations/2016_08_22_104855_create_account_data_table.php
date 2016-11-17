<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('account_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account')->index();
            $table->integer('platform_id')->unsigned()->index();
            $table->foreign('platform_id')->references('id')
            ->on('platforms')->onDelete('cascade');
            $table->integer('phone');
            $table->string('email',50);
            $table->boolean('is_lock')->default(0);
            $table->string('country',30);
            $table->date('birth');
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
        Schema::drop('account_datas');
    }
}
