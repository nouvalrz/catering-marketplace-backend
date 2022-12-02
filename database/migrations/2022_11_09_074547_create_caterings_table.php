<?php

use App\Models\District;
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
            $table->string('name');
            $table->text('description');
            $table->string('phone', 20);
            $table->text('address');
            $table->foreignIdFor(District::class);
            $table->integer('zipcode');
            $table->decimal('latitude', $precision = 8, $scale = 6);
            $table->decimal('longitude', $precision = 9, $scale = 6);
            $table->time('delivery_start_time');
            $table->time('delivery_end_time');
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
