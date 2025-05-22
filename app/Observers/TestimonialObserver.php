<?php

namespace App\Observers;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class TestimonialObserver
{
    public function creating(Testimonial $testimonial)
    {
        $this->setSortOrder($testimonial);
    }

    public function updated(Testimonial $testimonial)
    {
        $this->deleteCache($testimonial);
    }

    public function created(Testimonial $testimonial)
    {
        $this->deleteCache($testimonial);
    }

    public function deleted(Testimonial $testimonial)
    {
        $this->deleteCache($testimonial);
    }

    private function setSortOrder($testimonial)
    {
        (new Testimonial)->increment('sort_order');
        $testimonial->sort_order = 1;
    }

    public function deleteCache(Testimonial $testimonial)
    {
        if ($testimonial->contentBlocks()->count()) {
            foreach ($testimonial->contentBlocks as $contentBlock) {
                $resource = $contentBlock->resource;
                if ($resource) {
                    Cache::forget(strtolower(class_basename($resource)) . "-" . $resource->slug);
                    Cache::forget(strtolower(class_basename($resource)) . "-" . $resource->slug . "-all");
                }
            }
        }

        if ($testimonial->projects()->count()) {
            foreach ($testimonial->projects as $project) {
                Cache::forget("project-$project->slug");
                Cache::forget("project-$project->slug-all");
            }
        }
        if ($testimonial->stories()->count()) {
            foreach ($testimonial->stories as $story) {
                Cache::forget("story-$story->slug");
                Cache::forget("story-$story->slug-all");
            }
        }
    }
}
