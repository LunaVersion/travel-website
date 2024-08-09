<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Adventure Travel'],
            ['id' => 2, 'name' => 'Beach'],
            ['id' => 3, 'name' => 'Explore World'],
            ['id' => 4, 'name' => 'Family Holidays'],
            ['id' => 5, 'name' => 'Art and culture'],
        ]);
    }
}
