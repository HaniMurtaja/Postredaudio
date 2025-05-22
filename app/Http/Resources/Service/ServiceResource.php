<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Project\SimpleProjectResource;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'projects' => SimpleProjectResource::collection($this->projects),
        ];
    }
}
