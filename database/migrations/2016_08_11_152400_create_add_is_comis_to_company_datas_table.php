<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddIsComisToCompanyDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_datas',function(Blueprint $table){
            $table->integer('is_comis')->unsigned()->index()->after('credit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('company_datas', function (Blueprint $table) {
            $table->dropColumn('is_comis');
        });
    }
}
