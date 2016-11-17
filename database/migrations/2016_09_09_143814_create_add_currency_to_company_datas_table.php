<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddCurrencyToCompanyDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_datas',function(Blueprint $table){
            $table->string('currency',50)->index()->after('country')->comment('貨幣代碼');
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
            $table->dropColumn('currency');
        });
    }
}
