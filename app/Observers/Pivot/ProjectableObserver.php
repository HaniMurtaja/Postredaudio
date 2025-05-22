<?php

namespace App\Observers\Pivot;

use App\Models\Pivot\Projectable;
use Illuminate\Support\Facades\Cache;

class ProjectableObserver
{
    public function created(Projectable $projectable): void
    {
        $this->deleteCache($projectable);
    }

    public function updated(Projectable $projectable): void
    {
        $this->deleteCache($projectable);
    }

    public function deleted(Projectable $projectable): void
    {
        $this->deleteCache($projectable);
    }

    private function deleteCache(Projectable $projectable)
    {
        $resourceModel = $projectable->projectable_type;
        $resourceType = strtolower(class_basename($resourceModel));
        $resourceSlug = $resourceModel::find($projectable->projectable_id)->slug;

        Cache::forget("$resourceType-$resourceSlug");
        Cache::forget("$resourceType-$resourceSlug-all");
    }
}
