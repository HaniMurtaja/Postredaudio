<?php

namespace App\Observers;

use App\Models\TeamMember;
use App\Models\ContentBlock;
use Illuminate\Support\Facades\Cache;

class TeamMemberObserver
{
    public function updated(TeamMember $teamMember)
    {
        $this->deleteCache($teamMember);
    }

    public function created(TeamMember $teamMember)
    {
        $this->deleteCache($teamMember);
    }

    public function deleted(TeamMember $teamMember)
    {
        $this->deleteCache($teamMember);
    }

    public function deleteCache(TeamMember $teamMember)
    {
        $contentBlocks = ContentBlock::whereHas('departments.teamMembers', function ($q) use ($teamMember) {
            $q->where('id', $teamMember->id);
        })->get();

        foreach ($contentBlocks as $contentBlock) {
            $resource = $contentBlock->resource;
            $resourceType = strtolower(class_basename(get_class($resource)));

            Cache::forget("$resourceType-$resource->slug");
            Cache::forget("$resourceType-$resource->slug-all");
        }
    }
}
