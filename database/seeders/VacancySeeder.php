<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Storage;

class VacancySeeder extends Seeder
{
    public function run(): void
    {
        $vacancies = json_decode(Storage::disk('storage')->get('content.json'), true)['vacancies'];

        foreach ($vacancies as $vacancy) {
            Vacancy::create([
                'title' => $vacancy['title'],
                'about' => $vacancy['about'],
                'description' => $vacancy['description'],
                'responsibilities' => createFlexibleField($vacancy['responsibilities'], 'responsibility'),
                'requirements' => createFlexibleField($vacancy['requirements'], 'requirement'),
                'skills' => createFlexibleField($vacancy['skills'], 'skill'),
                'active' => true,
            ]);
        }
    }
}
