<?php

namespace App\Observers;

use App\Models\Story;
use Illuminate\Support\Facades\Cache;
use App\Models\HomeGalleryItem;
use Illuminate\Support\Facades\DB;

class StoryObserver
{
    public function creating(Story $story)
    {
        $this->setSlug($story);
        $this->setSortOrder($story);
    }

    public function updating(Story $story)
    {
        if ($story->isDirty('title')) $this->setSlug($story);
    }

    public function updated(Story $story)
    {
        $this->deleteCache($story);
        $this->setHomeGalleryItem($story);
    }

    public function created(Story $story)
    {
        $this->deleteCache($story);
        $this->setHomeGalleryItem($story);
    }

    public function deleted(Story $story)
    {
        $this->deleteCache($story);
        $this->deleteHomeGalleryItem($story);
    }

    private function setSortOrder($story)
    {
        (new Story)->increment('sort_order');
        $story->sort_order = 1;
    }

    public function deleteCache(Story $story)
    {
        Cache::forget("resources");
        Cache::forget("resources-all");
        Cache::forget("gallery");
        Cache::forget("gallery-all");
        $storySlugs = Story::pluck('slug')->toArray();

        if (count($storySlugs)) {
            foreach (range(1, intval(ceil(count($storySlugs) / 15))) as $storyPage) {
                Cache::forget("stories-$storyPage");
                Cache::forget("stories-$storyPage-all");
            }
        }

        if ($story->wasChanged('slug')) {
            Cache::forget("story-" . $story->getOriginal('slug'));
            Cache::forget("story-" . $story->getOriginal('slug') . "-all");
        }

        foreach ($storySlugs as $slug) {
            Cache::forget("story-$slug");
            Cache::forget("story-$slug-all");
        }
    }

    private function setSlug(Story $story)
    {
        $story->slug = rtrim(urlencode(preg_replace('/\W+/', '-', strtolower(trim($story->title)))), "-");
    }

    public function setHomeGalleryItem(Story $story)
    {
        if ($story->show_in_gallery && !$story->homeGalleryItem) {
            DB::table('home_gallery_items')->increment('sort_order');

            HomeGalleryItem::create([
                'resource_id' => $story->id,
                'resource_type' => get_class($story),
                'title' => $story->title,
                'sort_order' => 1,
            ]);
        } else if (!$story->show_in_gallery && $story->homeGalleryItem) {
            $this->deleteHomeGalleryItem($story);
        }
    }

    public function deleteHomeGalleryItem(Story $story)
    {
        $story->homeGalleryItem()->delete();
    }
}
