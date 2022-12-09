<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Customer::class);
            $table->string('recipient_name');
            $table->string('address');
            $table->foreignIdFor(\App\Models\District::class);
            $table->integer('zipcode');
            $table->decimal('latitude', $precision = 8, $scale = 6);
            $table->decimal('longitude', $precision = 9, $scale = 6);
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
        Schema::dropIfExists('customer_addresses');
    }
}
