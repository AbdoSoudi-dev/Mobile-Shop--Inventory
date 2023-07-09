<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCenterRepairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_center_repairs', function (Blueprint $table) {
            $table->id();
            $table->string("title")->index();

            $table->string("problem");
            $table->string("client_name");
            $table->bigInteger("client_number")->nullable();

            $table->string("serial_no")->nullable();
            $table->string("notes")->nullable();
            $table->boolean("removed")->default(0);
            $table->boolean("received")->default(0);

            $table->foreignId("brand_id")->nullable()->constrained();

            $table->foreignId("user_id")->constrained();
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
        Schema::dropIfExists('service_center_repairs');
    }
}
