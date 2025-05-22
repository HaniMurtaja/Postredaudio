<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentBlock\ContentBlockResource;
use App\Http\Resources\Project\SimpleProjectResource;
use App\Http\Resources\TestimonialResource;

class SingleServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'content' => ContentBlockResource::collection($this->relationLoaded('contentBlocks') ? $this->contentBlocks : $this->activeContentBlocks),
            'projects' => SimpleProjectResource::collection($this->relationLoaded('projects') ? $this->projects : $this->activeProjects),
            'project_section_label' => $this->project_section_label,
            'displayed_projects' => SimpleProjectResource::collection($this->projectables)
        ];
    }
}
