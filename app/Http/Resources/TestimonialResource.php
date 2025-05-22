<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\Project\SimpleProjectResource;

class TestimonialResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'profession' => $this->profession,
            'text' => $this->text,
            'links' => array_map(function ($linkItem) {
                return $linkItem;
            }, $this->links->toArray()),
            'client' => new ClientResource($this->client),
            'projects' => SimpleProjectResource::collection($this->projects),
            'achievements' => AchievementResource::collection($this->achievements),
            'image' => getSingleImageCollection($this, 'image')
        ];
    }
}
