<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\Project;

class ModuleResource extends JsonResource
{
    protected static $project;

    public function toArray($request)
    {
        return $this->name;
    }
}
