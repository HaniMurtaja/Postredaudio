<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\Achievement;
use Illuminate\Support\Facades\Storage;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        Storage::deleteDirectory('public/testimonial');
        Storage::makeDirectory('public/testimonial');
        $testimonials = json_decode(Storage::disk('storage')->get('content.json'), true)['testimonials'];

        foreach ($testimonials as $testimonialData) {
            $testimonial = Testimonial::create([
                'name' => $testimonialData['name'],
                'profession' => $testimonialData['profession'],
                'text' => $testimonialData['text'],
                'links' => createFlexibleField($testimonialData['links'], 'link_item'),
            ]);

            if (isset($projectData['achievements'])) {
                $achievementIds = Achievement::whereIn('name', $projectData['achievements'])->pluck('id')->toArray();

                if ($achievementIds) $testimonial->achievements()->attach($achievementIds);
            }

            $testimonial->addMedia(storage_path($testimonialData['image']))
                ->preservingOriginal()
                ->toMediaCollection('image');
        }
    }
}
