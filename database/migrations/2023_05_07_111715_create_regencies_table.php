<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegenciesTable extends Migration
{
    public function up()
    {
        Schema::create('regencies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('province_id',)->unsigned();
            $table->string('name',191);
        });
    }

    public function down()
    {
        Schema::dropIfExists('regencies');
    }
}
