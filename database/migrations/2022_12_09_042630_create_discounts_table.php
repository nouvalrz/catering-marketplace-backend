<?php


use Brick\Math\BigInteger;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Catering::class);
            $table->string('type');
            $table->string('title');
            $table->string('description');
            $table->float('percentage', 8, 2);
            $table->BigInteger('minimum_spend');
            $table->BigInteger('maximum_disc');
            $table->dateTimeTz('start_date', $precision = 0);
            $table->dateTimeTz('end_date', $precision = 0);
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
        Schema::dropIfExists('discounts');
    }
}
