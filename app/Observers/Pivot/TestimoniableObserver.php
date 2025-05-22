<?php

namespace App\Observers\Pivot;

use App\Models\Pivot\Testimoniable;
use Illuminate\Support\Facades\Cache;

class TestimoniableObserver
{
    public function created(Testimoniable $testimoniable): void
    {
        $this->deleteCache($testimoniable);
    }

    public function updated(Testimoniable $testimoniable): void
    {
        $this->deleteCache($testimoniable);
    }

    public function deleted(Testimoniable $testimoniable): void
    {
        $this->deleteCache($testimoniable);
    }

    private function deleteCache(Testimoniable $testimoniable)
    {
        $resourceModel = $testimoniable->testimoniable_type;
        $resourceType = strtolower(class_basename($resourceModel));
        $resourceSlug = $resourceModel::find($testimoniable->testimoniable_id)->slug;

        Cache::forget("$resourceType-$resourceSlug");
        Cache::forget("$resourceType-$resourceSlug-all");
    }
}
