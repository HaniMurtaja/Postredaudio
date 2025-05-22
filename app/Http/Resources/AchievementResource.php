<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'image' => getSingleImageCollection($this, 'logo')
        ];
    }
}
