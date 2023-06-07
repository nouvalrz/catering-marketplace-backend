<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCateringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caterings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name',191);
            $table->string('email',191);
            $table->text('description');
            $table->string('phone',20);
            $table->text('address');
            $table->double('balance')->default(0);
            $table->bigInteger('province_id',)->nullable();
            $table->bigInteger('regency_id',)->nullable();
            $table->bigInteger('district_id',)->nullable();
            $table->bigInteger('village_id',)->unsigned()->nullable();
            $table->integer('zipcode',);
            $table->decimal('latitude',30,15)->nullable();
            $table->decimal('longitude',30,15)->nullable();
            $table->time('delivery_start_time')->nullable();
            $table->time('delivery_end_time')->nullable();
            $table->text('image')->nullable();
            $table->enum('isVerified',['yes','no']);
            $table->bigInteger('user_id',)->unsigned();
            $table->integer('total_sales',)->default(0);
            $table->text('workday')->nullable();
            $table->integer('delivery_cost',)->nullable();
            $table->integer('min_distance_delivery',)->nullable();
            $table->float('rate')->nullable();
            $table->tinyInteger('is_open',)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caterings');
    }
}
