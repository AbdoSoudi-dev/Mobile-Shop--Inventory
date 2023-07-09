<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientIdToServiceCenterRepairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_center_repairs', function (Blueprint $table) {
            $table->dropColumn(["client_name","client_number"]);
//            $table->dropColumn();

            $table->foreignId("client_id")->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_center_repairs', function (Blueprint $table) {

        });
    }
}
