<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCateringCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catering_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('catering_id',)->unsigned();
            $table->bigInteger('categories_id',)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catering_categories');
    }
}
