<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuRolePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_role', function (Blueprint $table) {
            $table->integer('menu_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('menu_id')->references('id')
            ->on('menus')->onDelete('cascade');
            $table->foreign('role_id')->references('id')
            ->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menu_role');
    }
}
