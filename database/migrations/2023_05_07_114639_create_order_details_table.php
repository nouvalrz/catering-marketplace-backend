<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('orders_id',)->unsigned();
            $table->bigInteger('product_id',)->unsigned();
            $table->integer('quantity',);
            $table->bigInteger('price',);
            $table->datetime('delivery_datetime');
            $table->enum('status',['pending','sending','delivered']);
            $table->text('custom_desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
