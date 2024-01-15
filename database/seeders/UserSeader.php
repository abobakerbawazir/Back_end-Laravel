<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeader extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users=[[
            "username"=>"Ali",
                   "phone"=>"775630183",
                   "full_name"=>"Ali Bawazir",
                   "email"=>"Ali@gmail.com",
                   "password"=>"775630183",
                   "password_confirmation"=>"775630183",
                   "role"=>"admin"
        ],[
            "username"=>"Ahmed",
                   "phone"=>"777132088",
                   "full_name"=>"Ahmed Bawazir",
                   "email"=>"Ahmed@gmail.com",
                   "password"=>"777132088",
                   "password_confirmation"=>"777132088",
                   "role"=>"admin"
           ]];
           

        //
    }
}
