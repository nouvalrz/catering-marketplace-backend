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
                "name" => "Aneka Nasi",
                "description" => ""
            ],
                        [
                "name" => "Vegertarian",
                "description" => ""
            ],
                        [
                "name" => "Bali",
                "description" => ""
            ],
                        [
                "name" => "Jawa",
                "description" => ""
            ],
                        [
                "name" => "Padang",
                "description" => ""
            ],
                        [
                "name" => "Mie",
                "description" => ""
            ],
                        [
                "name" => "Jajan",
                "description" => ""
            ],

        ];

        Categories::insert($categories);

    }
}
