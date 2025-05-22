<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\CastResource;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\Industry\SimpleIndustryResource;
use App\Http\Resources\Service\SimpleServiceResource;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'caption' => $this->caption,
            'pinned' => $this->pinned,
            'featured' => $this->featured,
            'cover_image' => getSingleImageCollection($this, 'cover_image', ['320']),
            'cover_image_mobile' => getSingleImageCollection($this, 'cover_image_mobile', ['320']),
            'secondary_image' => getSingleImageCollection($this, 'secondary_image'),
            'video' => getSingleImageCollection($this, 'video', ['thumb']),
            'video_mobile' => getSingleImageCollection($this, 'video_mobile', ['thumb']),
            'cast' => CastResource::collection($this->cast->filter(fn ($cast) => $cast->show_in_list))->groupBy('position'),
            'thumbnail_info' => CastResource::collection($this->cast->filter(fn ($cast) => $cast->show_in_thumbnail))->groupBy('position'),
            'key_position' => CastResource::collection($this->cast->filter(fn ($cast) => $cast->key_role))->groupBy('position'),
            'client' => $this->client ? $this->client->name : null,
            'industry' => new SimpleIndustryResource($this->industry),
            'services' => SimpleServiceResource::collection($this->services),
            'achievements' => AchievementResource::collection($this->achievements),
            'modules' => ModuleResource::collection($this->modules)
        ];
    }
}
