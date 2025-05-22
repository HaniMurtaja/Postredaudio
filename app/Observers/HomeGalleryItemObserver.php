<?php

namespace App\Observers;

use App\Models\HomeGalleryItem;
use Illuminate\Support\Facades\Cache;

class HomeGalleryItemObserver
{
    public function created(HomeGalleryItem $homeGalleryItem)
    {
        $this->deleteCache();
    }

    public function updated(HomeGalleryItem $homeGalleryItem)
    {
        $this->deleteCache();
    }

    public function deleted(HomeGalleryItem $homeGalleryItem)
    {
        $this->deleteCache();
    }

    public function deleteCache()
    {
        Cache::forget("gallery");
        Cache::forget("gallery-all");
    }
}
