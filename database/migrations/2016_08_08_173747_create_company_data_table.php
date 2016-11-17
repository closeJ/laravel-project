<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')
            ->on('users')->onDelete('cascade');
            $table->string('country',50)->index();
            $table->integer('phone')->unsigned();
            $table->integer('company_phone')->unsigned();
            $table->string('address');
            $table->boolean('is_lock')->default(0);
            $table->string('lock_time')->comment('封鎖時間');
            $table->integer('credit')->unsigned()->index()->comment('信用額度');
            $table->integer('commission')->unsigned()->comment('佣金');
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
        Schema::drop('company_datas');
    }
}
