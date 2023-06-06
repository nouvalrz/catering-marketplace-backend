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
            $table->timestamps();

            $table->bigInteger('customer_id',)->unsigned();
            $table->bigInteger('customer_addresses_id',)->unsigned();
            $table->bigInteger('catering_id',)->unsigned();
            $table->string('invoice_number',191)->nullable();
            $table->string('note',191);
            $table->bigInteger('delivery_cost',);
            $table->bigInteger('total_price',);
            $table->enum('order_type',['preorder','subs']);
            $table->enum('status',['UNPAID','PAID','VOID','PENDING','NOT_APPROVED','PROCESSED','SEND','ONGOING','ACCEPTED','COMPLAINT'])->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('cancele_at');
            $table->tinyInteger('has_review')->nullable();
            $table->datetime('payment_expiry')->nullable();
            $table->text('snap_token');
            $table->text('diskon')->nullable();
            $table->integer('use_balance')->nullable();
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
