<?php

namespace Database\Seeders;

use App\Models\Administrators;
use App\Models\Categories;
use App\Models\Catering;
use App\Models\District;
use App\Models\Image;
use App\Models\Product;
use App\Models\Regency;
use App\Models\Slider;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // User Data
        User::truncate();
        $this->call(UserSeeder::class);
        Administrators::truncate();
        $this->call(AdminSeed::class);
        Slider::truncate();
        $this->call(SliderSeeder::class);


        // Master Data

        Village::truncate();
        District::truncate();
        Regency::truncate();
        District::truncate();
        $this->call(LocationSeeder::class);

        // Master Business Data
        Categories::truncate();

        $this->call(CategorySeeder::class);

        // Business Data
//        Image::truncate();
        Catering::truncate();
        Product::truncate();

//        $this->call(CateringImageSeeder::class);
        $this->call(CateringSeeder::class);
//        $this->call(CateringProductImageSeeder::class);
        $this->call(CateringProductSeeder::class);
    }
}
