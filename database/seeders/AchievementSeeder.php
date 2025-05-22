<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        Storage::deleteDirectory('public/achievement');
        Storage::makeDirectory('public/achievement');
        
        $data = json_decode(Storage::disk('storage')->get('content.json'), true)['achievements'];

        foreach($data as $achievementData) {
            $achievement = Achievement::create([
                'name' => $achievementData['name']
            ]);

            $imagePath = storage_path((imageExists($achievementData['image']) ? $achievementData['image'] : 'images/achievements/achievement-generic.svg'));
            $achievement->addMedia($imagePath)
                ->preservingOriginal()
                ->toMediaCollection('logo');
        }
    }
}
