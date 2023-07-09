<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_logs', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("price");
            $table->string("selling_price");
            $table->string("serial_no");
            $table->string("notes");
            $table->string("quantity");

            $table->foreignId("product_id")->constrained();
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
        Schema::dropIfExists('products_logs');
    }
}
