<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('product_id',);
            $table->string('option_name',100)->nullable();
            $table->enum('option_type',['single','multiple'])->nullable();
            $table->integer('maximum_selection',)->nullable();
            $table->integer('minimum_selection',)->nullable();
            $table->tinyInteger('is_active',)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_options');
    }
}
