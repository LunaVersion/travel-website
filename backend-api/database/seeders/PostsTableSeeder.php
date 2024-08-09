<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('posts')->insert([
            [
                'category_id' => 1, 
                'title' => 'Where can i go? 5 amazing countries that are open right now',
                'slug' => Str::slug('Where can i go? 5 amazing countries that are open right now'),
                'content' => 'enim lobortis scelerisque fermentum dui faucibus in ornare quam viverra 
                                orci sagittis eu volutpat odio facilisis mauris sit amet massa vitae tortor condimentum 
                                lacinia quis vel eros donec ac odio tempor orci dapibus ultrices in iaculis nunc sed 
                                augue lacus, viverra vitae congue eu, consequat ac felis donec et odio pellentesque diam 
                                volutpat commodo sed egestas egestas fringilla fau.',
                'status' => 1, // Published
                'image' => 'image1.jpg',
                'draft' => json_encode([
                    'category_id' => 1,
                    'title' => 'Sample Post Title 1',
                    'slug' => Str::slug('Sample Post Title 1'),
                    'content' => 'content.......',
                    'status' => 0,
                    'image' => 'image1.jpg',
                ]),
                'created_at' => '2024-08-10 14:30:00',
                'updated_at' => '2024-08-10 14:30:00',
            ],
            [
                'category_id' => 2, 
                'title' => 'Where can i go?......',
                'slug' => Str::slug('Where can i go?......'),
                'content' => 'enim lobortis scelerisque fermentum dui faucibus....',
                'status' => 2, // Unpublished
                'image' => 'image2.jpg',
                'draft' => null, 
                'created_at' => '2024-08-10 14:30:00',
                'updated_at' => '2024-08-10 14:30:00',
            ],
        ]);
    }
}

