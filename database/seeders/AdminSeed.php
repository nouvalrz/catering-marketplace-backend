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
                "email"=>"admin1@gmail.com",
                "password"=>bcrypt("password"),
                "type"=>"admin",
                // "user_id"=>"5"
            ],
        ]);
    }
}
