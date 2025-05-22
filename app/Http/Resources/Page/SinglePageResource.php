<?php

namespace App\Http\Resources\Page;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentBlock\ContentBlockResource;
use App\Http\Resources\Project\SimpleProjectResource;

class SinglePageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'page_type' => array('standard', 'special', 'external')[(int) $this->page_type],
            'content' => ContentBlockResource::collection($this->relationLoaded('contentBlocks') ? $this->contentBlocks : $this->activeContentBlocks),
            'project_section_label' => $this->project_section_label,
            'displayed_projects' => SimpleProjectResource::collection($this->relationLoaded('projects') ? $this->projects : $this->activeProjects)
        ];
    }
}
