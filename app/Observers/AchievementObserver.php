<?php

namespace App\Observers;

use App\Models\Achievement;
use Illuminate\Support\Facades\Cache;

class AchievementObserver
{
    public function updated(Achievement $achievement)
    {
        $this->deleteCache($achievement);
    }

    public function created(Achievement $achievement)
    {
        $this->deleteCache($achievement);
    }

    public function deleted(Achievement $achievement)
    {
        $this->deleteCache($achievement);
    }

    public function deleteCache($achievement)
    {
        $projects = $achievement->projects;

        if ($projects->count()) {
            Cache::forget("projects");
            Cache::forget("projects-all");

            foreach ($projects as $project) {
                Cache::forget("project-$project->slug");
                Cache::forget("project-$project->slug-all");
            }
        }
    }
}
