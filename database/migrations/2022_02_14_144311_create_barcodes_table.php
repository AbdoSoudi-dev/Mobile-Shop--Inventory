<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcodes', function (Blueprint $table) {
            $table->id();
            $table->string("title");

            $table->string("barcode")->index()->unique();

            $table->string("image")->nullable();
            $table->boolean("status")->default(0);

            $table->string("serial_no")->nullable();

            $table->foreignId("brand_id")->nullable()->constrained();
            $table->foreignId("category_id")->constrained();
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
        Schema::dropIfExists('barcodes');
    }
}
