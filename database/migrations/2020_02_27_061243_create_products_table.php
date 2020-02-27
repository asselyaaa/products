<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description');
            $table->integer('price')->unsigned();
            $table->integer('amount')->nullable()->default(0);
            $table->integer('weight')->nullable();
            $table->integer('height')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->timestamps();

            $table->foreign('color_id')->references('id')->on('colors');
            $table->index('price');
            $table->index('color_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
