<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_activities', function (Blueprint $table) {
            $table->id();

            $table->decimal("debit")->default(0);
            $table->decimal("credit")->default(0);
            $table->decimal("bank_amount")->default(0);

            $table->string("notes")->nullable();
            $table->string("type")->default("قيد");

            $table->boolean("removed")->default(0);
            $table->boolean("refund")->default(0);

            $table->decimal("fawry_balance")->default(0);
            $table->decimal("damen_balance")->default(0);

            $table->decimal("unpaid_debit")->default(0);

            $table->foreignId("bank_id")->nullable()->constrained();
            $table->foreignId("financial_accounts_type_id")->nullable()->constrained();

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
        Schema::dropIfExists('financial_activities');
    }
}
