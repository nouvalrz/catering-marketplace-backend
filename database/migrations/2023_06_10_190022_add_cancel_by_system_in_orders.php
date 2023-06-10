<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelBySystemInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE orders CHANGE COLUMN status status ENUM('UNPAID', 'PAID', 'VOID', 'PENDING', 'NOT_APPROVED', 'CANCEL_BY_SYSTEM', 'PROCESSED', 'SEND', 'ONGOING', 'ACCEPTED', 'COMPLAINT') NOT NULL DEFAULT 'UNPAID'");
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
