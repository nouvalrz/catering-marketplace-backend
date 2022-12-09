<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Customer::class);
            $table->foreignIdFor(\App\Models\CustomerAddresses::class);
            $table->string('note');
            $table->string('delivery_type');
            $table->bigInteger('delivery_cost');
            $table->bigInteger('total_price');
            $table->string('order_type');
            $table->dateTimeTz('start_date', $precision = 0);
            $table->dateTimeTz('end_date', $precision = 0);
            $table->dateTimeTz('cancele_at', $precision = 0);
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
        Schema::dropIfExists('orders');
    }
}
