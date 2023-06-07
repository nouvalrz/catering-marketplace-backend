<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->bigInteger('catering_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->string('bank_name',191);
            $table->string('account_name',191);
            $table->string('bank_account',191);
            $table->integer('nominal');
            $table->enum('approved',['pending','accepted','rejected','done'])->nullable();
            $table->enum('role',['catering','customer'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_withdrawals');
    }
}
