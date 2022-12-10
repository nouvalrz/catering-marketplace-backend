<?php

use App\Models\District;
use App\Models\Village;
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
            $table->string('name');
            $table->string('email')->unique();
            $table->text('description')->nullable();
            $table->string('phone', 20);
            $table->text('address');
            $table->foreignIdFor(Village::class)->nullable();
            $table->integer('zipcode');
            $table->decimal('latitude', $precision = 8, $scale = 6)->nullable();
            $table->decimal('longitude', $precision = 9, $scale = 6)->nullable();
            $table->time('delivery_start_time')->nullable();
            $table->time('delivery_end_time')->nullable();
            $table->foreignIdFor(\App\Models\Image::class)->nullable();
            $table->enum('isVerified',['yes', 'no']);
            $table->foreignIdFor(\App\Models\User::class);
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
        Schema::dropIfExists('caterings');
    }
}
