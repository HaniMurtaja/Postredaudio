<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use App\Enums\CastPosition;
use App\Models\Project;
use App\Models\Achievement;
use App\Models\Client;
use App\Models\Module;
use App\Models\Service;
use App\Models\Cast;
use App\Models\Industry;
use App\Models\Testimonial;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Storage::deleteDirectory('public/project');
        Storage::makeDirectory('public/project');

        $data = json_decode(Storage::disk('storage')->get('content.json'), true)['projects'];

        foreach ($data as $projectData) {
            $project = Project::withoutEvents(function () use ($projectData) {
                return Project::create([
                    'title' => $projectData['title'],
                    'slug' => rtrim(urlencode(preg_replace('/\W+/', '-', strtolower(trim($projectData['title'])))), "-"),
                    'sort_order' => Project::count() + 1,
                    'caption' => $projectData['description'] ?? null,
                    'description' => $projectData['description'] ?? null,
                    'client_id' => isset($projectData['client']) && Client::where('name', $projectData['client'])->exists() ?
                        Client::where('name', $projectData['client'])->pluck('id')->first() :
                        Client::inRandomOrder()->pluck('id')->first(),
                    'industry_id' => isset($projectData['industry']) && Industry::where('name', $projectData['industry'])->exists() ?
                        Industry::where('name', $projectData['industry'])->pluck('id')->first() :
                        Industry::inRandomOrder()->pluck('id')->first(),
                    'pinned' => $projectData['pinned'] ?? false,
                    'featured' => $projectData['featured'] ?? false,
                ]);
            });

            if (isset($projectData['testimonials'])) {
                $project->testimonials()->attach(Testimonial::whereIn('name', $projectData['testimonials'])->get());
            }

            if ($project->pinned) {
                $index = 0;
                $modulesWithOrder = Module::take(5)
                    ->pluck('id')
                    ->flatMap(function ($module) use (&$index) {
                        $index++;

                        return [
                            "$module " => ['sort_order' => $index]
                        ];
                    });

                $project->modules()->attach($modulesWithOrder);
            }

            foreach ($projectData['cast'] as $index => $castMember) {
                $cast = Cast::where([
                    ['name', $castMember['name']],
                    ['position', $castMember['position']],
                    ['key_role', $castMember['key_role']],
                    ['show_in_list', $castMember['show_in_list']],
                ])->first();

                if (!$cast) {
                    $cast = Cast::create([
                        'name' => $castMember['name'],
                        'position' => $castMember['position'],
                        'key_role' => $castMember['key_role'],
                        'show_in_list' => $castMember['show_in_list'],
                    ]);
                }

                $project->cast()->attach($cast->id, ['sort_order' => $index + 1]);
            }

            if (isset($projectData['services'])) {
                $serviceIds = Service::whereIn('name', $projectData['services'])->pluck('id')->toArray();
                $project->services()->attach($serviceIds);
            }

            if (isset($projectData['achievements'])) {
                $achievementIds = Achievement::whereIn('name', $projectData['achievements'])->pluck('id')->toArray();
                $project->achievements()->attach($achievementIds);
            }

            if (imageExists($projectData['image'])) {
                $project->addMedia(storage_path($projectData['image']))
                    ->preservingOriginal()
                    ->toMediaCollection('cover_image');
            }

            if (imageExists($projectData['secondary_image'])) {
                $project->addMedia(storage_path($projectData['secondary_image']))
                    ->preservingOriginal()
                    ->toMediaCollection('secondary_image');
            }
        }
    }
}
