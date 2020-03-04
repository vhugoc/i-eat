<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->string('company')->nullable(false);
            $table->string('phone')->nullable(true);
            $table->string('landline')->nullable(true);
            $table->string('email')->nullable(false);
            $table->string('password')->nullable(false);
            $table->string('thumb_uri')->nullable(true);
            $table->integer('splan_id')->nullable(false);
            $table->string('cnpj')->nullable(true);
            $table->string('city')->nullable(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('status')->default(false);
            $table->string('ip')->nullable(true);
            $table->string('timezone')->default('America/Sao_Paulo');
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
        Schema::dropIfExists('users');
    }
}
