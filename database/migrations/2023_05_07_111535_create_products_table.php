<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id',)->nullable();
            $table->bigInteger('catering_id',)->unsigned();
            $table->string('name',191);
            $table->text('description');
            $table->integer('weight',);
            $table->integer('price',);
            $table->integer('minimum_quantity',);
            $table->integer('maximum_quantity',);
            $table->tinyInteger('is_available',);
            $table->text('image');
            $table->integer('total_sales',)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
