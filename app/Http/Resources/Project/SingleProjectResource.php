<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Industry\SimpleIndustryResource;
use App\Http\Resources\Service\SimpleServiceResource;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\CastResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\TestimonialResource;

class SingleProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'caption' => $this->caption,
            'description' => $this->description,
            'cover_image' => getSingleImageCollection($this, 'cover_image', ['320']),
            'cover_image_mobile' => getSingleImageCollection($this, 'cover_image_mobile', ['320']),
            'video' => getSingleImageCollection($this, 'video', ['thumb']),
            'video_mobile' => getSingleImageCollection($this, 'video_mobile', ['thumb']),
            'video_url' => $this->video_url,
            'iframes' => array_map(function ($iframeItem) {
                return [
                    'iframe' => $iframeItem['iframe'],
                    'title' => $iframeItem['title'],
                    'description' => $iframeItem['description']
                ];
            }, $this->iframes->toArray()),
            'links' => array_key_exists('links', $this->resource->getAttributes()) ? flexibleStringValues($this->resource->getAttributes()['links']) : [],
            'cast' => CastResource::collection($this->cast->filter(fn ($cast) => $cast->show_in_list))->groupBy('position'),
            'thumbnail_info' => CastResource::collection($this->cast->filter(fn ($cast) => $cast->show_in_thumbnail))->groupBy('position'),
            'key_position' => CastResource::collection($this->cast->filter(fn ($cast) => $cast->key_role))->groupBy('position'),
            'client' => new ClientResource($this->client),
            'industry' => new SimpleIndustryResource($this->industry),
            'services' => SimpleServiceResource::collection($this->services),
            'achievements' => AchievementResource::collection($this->achievements),
            'testimonials' => TestimonialResource::collection($this->relationLoaded('testimonials') ? $this->testimonials : $this->activeTestimonials),
        ];
    }
}
