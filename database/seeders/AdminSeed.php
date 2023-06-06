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
        DB::table("users")->insert([
            [
                "name"=>"admin",
                "email"=>"admin@gmail.com",
                "password"=>bcrypt("admin123"),
                "type"=>"admin",
                // "user_id"=>"5"
            ],
        ]);
    }
}
