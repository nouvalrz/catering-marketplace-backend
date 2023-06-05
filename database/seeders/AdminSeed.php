<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("administrators")->insert([
            [
                "username"=>"admin@gmail.com",
                "password"=>"admin123",
                "fullname"=>"admin",
                "user_id"=>"5"
            ],
        ]);
    }
}
