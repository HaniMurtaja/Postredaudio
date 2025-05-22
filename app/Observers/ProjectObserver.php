<?php

namespace App\Observers;

use App\Models\HomeGalleryItem;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProjectObserver
{
    public function creating(Project $project)
    {
        $this->setSlug($project);
        $this->updatePinnedStatus($project);
        $this->setSortOrder($project);
    }

    public function updating(Project $project)
    {
        if ($project->isDirty('title')) $this->setSlug($project);

        $this->updatePinnedStatus($project);
    }

    public function updated(Project $project)
    {
        $this->deleteCache($project);
        $this->setHomeGalleryItem($project);
    }

    public function created(Project $project)
    {
        $this->deleteCache($project);
        $this->setHomeGalleryItem($project);
    }

    public function deleted(Project $project)
    {
        $this->deleteCache($project);
        $this->deleteHomeGalleryItem($project);
    }

    public function deleteCache(Project $project)
    {
        Cache::forget("resources");
        Cache::forget("resources-all");
        Cache::forget("projects");
        Cache::forget("projects-all");
        Cache::forget("gallery");
        Cache::forget("gallery-all");

        if ($project->wasChanged('slug')) {
            Cache::forget("project-" . $project->getOriginal('slug'));
            Cache::forget("project-" . $project->getOriginal('slug') . "-all");
        } else {
            Cache::forget("project-$project->slug");
            Cache::forget("project-$project->slug-all");
        }
    }

    private function setSortOrder($project)
    {
        (new Project)->increment('sort_order');
        $project->sort_order = 1;
    }

    private function setSlug(Project $project)
    {
        $project->slug = rtrim(urlencode(preg_replace('/\W+/', '-', strtolower(trim($project->title)))), "-");
    }

    private function updatePinnedStatus(Project $project)
    {
        if (!$project->pinned) {
            $project->modules()->detach();
        }
    }

    public function setHomeGalleryItem(Project $project)
    {
        if ($project->featured && !$project->homeGalleryItem) {
            DB::table('home_gallery_items')->increment('sort_order');

            HomeGalleryItem::create([
                'resource_id' => $project->id,
                'resource_type' => get_class($project),
                'title' => $project->title,
                'sort_order' => 1,
            ]);
        } else if (!$project->featured && $project->homeGalleryItem) {
            $this->deleteHomeGalleryItem($project);
        }
    }

    public function deleteHomeGalleryItem(Project $project)
    {
        $project->homeGalleryItem()->delete();
    }
}
