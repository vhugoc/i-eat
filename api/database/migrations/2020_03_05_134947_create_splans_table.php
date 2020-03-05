<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('splans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false);
            $table->integer('access')->nullable(false);
            $table->integer('max_posts')->nullable(false);
            $table->integer('max_employees')->nullable(false);
            $table->integer('max_products')->nullable(false);
            $table->string('payment')->default("monthly");
            $table->float('value', 7, 2)->nullable(false);
            $table->string('description')->nullable(false);
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
        Schema::dropIfExists('splans');
    }
}
