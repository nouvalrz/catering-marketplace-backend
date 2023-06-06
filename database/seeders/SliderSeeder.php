<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("sliders")->insert([
            [
                "title"=>"Download",
                "image"=>"2hZGPsRuSzrVC1Y19GATlQQwYjk7LlJdxEKPsD13.png",
                "is_active"=>1,
            ],
        ]);
    }
}
