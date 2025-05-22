<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->delete('base64.json');
        Storage::deleteDirectory('public/contentblock');
        Storage::makeDirectory('public/contentblock');

        $this->call([
            UserSeeder::class,
            ModuleSeeder::class,
            CategoryTypeSeeder::class,
            ColorSchemeSeeder::class,
            DepartmentSeeder::class,
            IndustrySeeder::class,
            ClientSeeder::class,
            TestimonialSeeder::class,
            AchievementSeeder::class,
            TeamMemberSeeder::class,
            PageSeeder::class,
            ServiceSeeder::class,
            ProjectSeeder::class,
            StorySeeder::class,
            ApprovedIpSeeder::class,
            VacancySeeder::class,
            PageHashFieldSeeder::class,
        ]);

        Cache::flush();
    }
}
