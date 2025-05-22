<?php

namespace App\Observers;

use App\Models\client;
use App\Models\ContentBlock;
use Illuminate\Support\Facades\Cache;

class ClientObserver
{
    public function updated(Client $client)
    {
        $this->deleteCache($client);
    }

    public function created(Client $client)
    {
        $this->deleteCache($client);
    }

    public function deleted(Client $client)
    {
        $this->deleteCache($client);
    }

    public function deleteCache(Client $client)
    {
        $projects = $client->projects;
        $contentBlocks = ContentBlock::whereJsonContains('content', [['layout' => 'client']])->get();
        foreach ($contentBlocks as $contentBlock) {
            $resource = $contentBlock->resource;
            $resourceType = strtolower(class_basename(get_class($resource)));
            Cache::forget("$resourceType-$resource->slug");
            Cache::forget("$resourceType-$resource->slug-all");
        }
        if ($projects) {
            Cache::forget("projects");
            Cache::forget("projects-all");
        }
        foreach ($projects as $project) {
            Cache::forget("project-$project->slug");
            Cache::forget("project-$project->slug-all");
        }
    }
}
