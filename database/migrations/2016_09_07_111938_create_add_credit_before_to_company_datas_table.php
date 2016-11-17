<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddCreditBeforeToCompanyDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_datas',function(Blueprint $table){
            $table->integer('credit_before')->unsigned()->index()->after('lock_time');
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
            $table->dropColumn('credit_before');
        });
    }
}
