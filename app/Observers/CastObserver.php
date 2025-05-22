<?php

namespace App\Observers;

use App\Models\Cast;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Exception;

class CastObserver
{
    public function updated(Cast $cast)
    {
        $this->deleteCache($cast);
    }

    public function created(Cast $cast)
    {
        $this->deleteCache($cast);
    }

    public function deleted(Cast $cast)
    {
        $this->deleteCache($cast);
    }

    public function deleteCache(Cast $cast)
    {
        $projects = $cast->projects;

        foreach ($projects as $project){
            Cache::forget("project-$project->slug");
            Cache::forget("project-$project->slug-all");

            Cache::forget("projects");
            Cache::forget("projects-all");
        }
    }
}
