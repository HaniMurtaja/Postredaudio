<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'position' => $this->position,
            'bio' => $this->bio,
            'links' => array_map(function ($linkItem) {
                return $linkItem;
            }, $this->links->toArray()),
            'image' => getSingleImageCollection($this, 'photo'),
        ];
    }
}
