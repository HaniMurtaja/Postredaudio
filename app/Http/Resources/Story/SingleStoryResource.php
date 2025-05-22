<?php

namespace App\Http\Resources\Story;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Story\StoryResource;
use App\Http\Resources\Service\SimpleServiceResource;
use App\Http\Resources\Project\SimpleProjectResource;
use App\Http\Resources\TestimonialResource;

class SingleStoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'date' => date_format($this->date, 'Y-m-d'),
            'author' => $this->author,
            'date_author_position' => $this->date_author_position ?? 'left',
            'cover_image'   => getSingleImageCollection($this, 'cover_image'),
            'cover_image_text' => $this->cover_image_text,
            'description' => $this->description,
            'content' => $this->content,
            'services' => SimpleServiceResource::collection($this->relationLoaded('services') ? $this->services : $this->activeServices),
            'recent_stories' => $this->recent_stories ? StoryResource::collection($this->recent_stories) : [],
            'project_section_label' => $this->project_section_label,
            'displayed_projects' => SimpleProjectResource::collection($this->relationLoaded('projects') ? $this->projects : $this->activeProjects),
            'testimonials' => TestimonialResource::collection($this->relationLoaded('testimonials') ? $this->testimonials : $this->activeTestimonials),
        ];
    }
}
