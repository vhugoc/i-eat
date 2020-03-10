<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Employees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable(false);
            $table->string('name', 80)->nullable(false);
            $table->string('email', 120)->nullable(false);
            $table->string('password', 64)->nullable(false);
            $table->integer('post_id')->nullable(false);
            $table->string('phone', 15)->nullable(true);
            $table->string('landline', 15)->nullable(true);
            $table->string('city', 80)->nullable(true);
            $table->string('district', 80)->nullable(true);
            $table->string('street', 80)->nullable(true);
            $table->string('complement', 80)->nullable(true);
            $table->time('get_in')->nullable(true);
            $table->time('get_out')->nullable(true);
            $table->string('thumb_uri')->nullable(true);
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
        Schema::dropIfExists('employees');
    }
}
