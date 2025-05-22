<?php

namespace App\Http\Resources\Story;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Service\SimpleServiceResource;

class StoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'pinned' => $this->featured,
            'date' => date_format($this->date, 'Y-m-d'),
            'author' => $this->author,
            'date_author_position' => $this->date_author_position ?? 'left',
            'description' => $this->description,
            'cover_image' => getSingleImageCollection($this, 'cover_image', ['320']),
            'cover_image_text' => $this->cover_image_text,
            'services' => SimpleServiceResource::collection($this->relationLoaded('services') ? $this->services : $this->activeServices),
        ];
    }
}
