<?php

namespace App\Observers;

use App\Models\Industry;
use Illuminate\Support\Facades\Cache;

class IndustryObserver
{
    public function creating(Industry $industry)
    {
        $this->setSlug($industry);
    }

    public function updating(Industry $industry)
    {
        if ($industry->isDirty('name')) $this->setSlug($industry);
    }

    public function updated(Industry $industry)
    {
        $this->deleteCache($industry);
    }

    public function created(Industry $industry)
    {
        $this->deleteCache($industry);
    }

    public function deleting(Industry $industry)
    {
        $industry->contentBlocks()->update(['resource_id' => null, 'resource_type' => null]);
    }

    public function deleted(Industry $industry)
    {
        $this->deleteCache($industry);
    }

    public function deleteCache(Industry $industry)
    {
        Cache::forget("resources");
        Cache::forget("resources-all");
        Cache::forget("industries");
        Cache::forget("industries-all");

        if ($industry->wasChanged('slug')) {
            Cache::forget("industry-" . $industry->getOriginal('slug'));
            Cache::forget("industry-" . $industry->getOriginal('slug') . "-all");
        } else {
            Cache::forget("industry-$industry->slug");
            Cache::forget("industry-$industry->slug-all");
        }
    }

    private function setSlug(Industry $industry)
    {
        $industry->slug = rtrim(urlencode(preg_replace('/\W+/', '-', strtolower(trim($industry->name)))), "-");
    }
}
