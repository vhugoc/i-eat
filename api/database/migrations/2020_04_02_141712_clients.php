<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Clients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable(false);
            $table->string('name', 80)->nullable(false);
            $table->string('phone', 15)->nullable(true);
            $table->string('landline', 15)->nullable(true);
            $table->string('city', 80)->nullable(true);
            $table->string('district', 80)->nullable(true);
            $table->string('street', 80)->nullable(true);
            $table->string('complement', 80)->nullable(true);
            $table->boolean('is_loyalty')->default(false);
            $table->date('birthday')->nullable(true);
            $table->string('obs', 200)->nullable(true);
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
        Schema::dropIfExists('clients');
    }
}
