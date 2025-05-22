<?php

namespace Database\Seeders;

use App\Models\Story;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        // Storage::deleteDirectory('public/story');
        // Storage::makeDirectory('public/story');

        // foreach(range(0, 10) as $index) {
        //     $story = Story::create([
        //         'title' => fake()->realTextBetween(20, 60),
        //         'date'  => fake()->date()
        //     ]);
        // }
    }
}
