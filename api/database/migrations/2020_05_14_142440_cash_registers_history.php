<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CashRegistersHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_registers_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cashier_id')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->integer('attendant_id')->nullable(true);
            $table->string('action')->default("open");
            $table->string('initial_value')->nullable(true);
            $table->string('final_value')->nullable(true);
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
        Schema::dropIfExists('cash_registers_history');
    }
}
