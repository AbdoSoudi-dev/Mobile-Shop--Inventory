<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();

            $table->decimal("price");
            $table->integer("quantity")->default(0);
            $table->integer("warranty_period")->nullable();

            $table->boolean("removed")->default(0);
            $table->boolean("refund")->default(0);

            $table->foreignId("product_id")->constrained();
            $table->foreignId("user_id")->constrained();

            $table->foreignId("service_center_repair_id")->nullable()->constrained();

            $table->foreignId("financial_activity_id")->constrained();

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
        Schema::dropIfExists('sales_invoices');
    }
}
