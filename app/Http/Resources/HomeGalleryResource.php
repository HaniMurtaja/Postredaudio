<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\Service\SimpleServiceResource;

class HomeGalleryResource extends JsonResource
{
    public function toArray($request)
    {
        $isProject = class_basename($this->galleryResource) === 'Project';

        return [
            'type' => $isProject ? 'projects' : 'stories',
            'sort_order' => $this->sort_order ?? 0,
            'title' => $this->galleryResource->title,
            'slug' => $this->galleryResource->slug,
            'caption' => $isProject ? $this->galleryResource->caption : $this->galleryResource->description,
            'cover_image' => getSingleImageCollection($this->galleryResource, 'cover_image', ['320']),
            'cover_image_mobile' => getSingleImageCollection($this->galleryResource, 'cover_image_mobile', ['320']),
            'video' => $isProject ? getSingleImageCollection($this->galleryResource, 'video', ['thumb']) : null,
            'video_mobile' => $isProject ? getSingleImageCollection($this->galleryResource, 'video_mobile', ['thumb']) : null,
            'services' => SimpleServiceResource::collection($this->galleryResource->services),
            'achievements' => $isProject ? AchievementResource::collection($this->galleryResource->achievements) : [],
        ];
    }
}
