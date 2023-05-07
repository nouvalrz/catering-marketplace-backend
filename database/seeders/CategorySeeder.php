<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $categories = [
            [
                "name" => "Aneka Nasi"
            ],
                        [
                "name" => "Vegertarian"
            ],
                        [
                "name" => "Bali"
            ],
                        [
                "name" => "Jawa"
            ],
                        [
                "name" => "Padang"
            ],
                        [
                "name" => "Mie"
            ],
                        [
                "name" => "Jajan"
            ],

        ];

        Categories::insert($categories);

    }
}
