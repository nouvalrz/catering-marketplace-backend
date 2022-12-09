<?php

use App\Models\Catering;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Catering::class);
            $table->string('name');
            $table->text('description');
            $table->integer('weight');
            $table->integer('price');
            $table->integer('minimum_quantity');
            $table->integer('maximum_quantity');
            $table->boolean('is_free_delivery');
            $table->boolean('is_hidden');
            $table->boolean('is_available');
            $table->foreignIdFor(\App\Models\Image::class);
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
