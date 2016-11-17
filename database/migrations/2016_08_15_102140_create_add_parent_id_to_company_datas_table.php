<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddParentIdToCompanyDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_datas',function(Blueprint $table){
            $table->integer('parent_id')->unsigned()->index()->after('user_id');
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
            $table->dropColumn('parent_id');
        });
    }
}
