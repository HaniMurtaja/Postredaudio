<?php

namespace App\Http\Resources\Industry;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Project\SimpleProjectResource;

class IndustryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'projects' => SimpleProjectResource::collection($this->relationLoaded('projects') ? $this->projects : $this->activeProjects)
        ];
    }
}
