<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Music',
                'description' => 'Concerts, festivals, and live music performances'
            ],
            [
                'name' => 'Sports',
                'description' => 'Games, tournaments, and sporting events'
            ],
            [
                'name' => 'Theater',
                'description' => 'Plays, musicals, and theatrical performances'
            ],
            [
                'name' => 'Conference',
                'description' => 'Business meetings, tech talks, and seminars'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
