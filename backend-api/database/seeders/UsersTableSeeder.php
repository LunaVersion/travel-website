<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Luna',
                'email' => 'lanhuong30032003@gmail.com',
                'password' => Hash::make('12345678'), 
            ],
            [
                'name' => 'Mina',
                'email' => 'mina@gmail.com',
                'password' => Hash::make('12345678910'), 
            ],
        ]);
    }
}
