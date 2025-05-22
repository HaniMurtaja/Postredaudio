<?php

namespace App\Observers;

use App\Models\Department;
use App\Models\ContentBlock;
use Illuminate\Support\Facades\Cache;

class DepartmentObserver
{
    public function updated(Department $department)
    {
        $this->deleteCache($department);
    }

    public function created(Department $department)
    {
        $this->deleteCache($department);
    }

    public function deleted(Department $department)
    {
        $this->deleteCache($department);
    }

    public function deleteCache(Department $department)
    {
        $contentBlocks = ContentBlock::whereHas('departments', function ($q) use ($department) {
            $q->where('id', $department->id);
        })->get();

        foreach ($contentBlocks as $contentBlock) {
            $resource = $contentBlock->resource;
            $resourceType = strtolower(class_basename(get_class($resource)));
            Cache::forget("$resourceType-$resource->slug");
            Cache::forget("$resourceType-$resource->slug-all");
        }
    }
}
