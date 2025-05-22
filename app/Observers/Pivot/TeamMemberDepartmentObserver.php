<?php

namespace App\Observers\Pivot;

use App\Models\Pivot\TeamMemberDepartment;
use App\Models\ContentBlock;
use Illuminate\Support\Facades\Cache;

class TeamMemberDepartmentObserver
{
    public function created(TeamMemberDepartment $teamMemberDepartment): void
    {
        $this->deleteCache($teamMemberDepartment);
    }

    public function updated(TeamMemberDepartment $teamMemberDepartment): void
    {
        $this->deleteCache($teamMemberDepartment);
    }

    public function deleted(TeamMemberDepartment $teamMemberDepartment): void
    {
        $this->deleteCache($teamMemberDepartment);
    }

    private function deleteCache(TeamMemberDepartment $teamMemberDepartment)
    {
        $contentBlocks = ContentBlock::whereHas('departments', function ($q) use ($teamMemberDepartment) {
            $q->where('id', $teamMemberDepartment->department_id);
        })->get();

        foreach ($contentBlocks as $contentBlock) {
            $resource = $contentBlock->resource;
            $resourceType = strtolower(class_basename(get_class($resource)));

            Cache::forget("$resourceType-$resource->slug");
            Cache::forget("$resourceType-$resource->slug-all");
        }
    }
}
