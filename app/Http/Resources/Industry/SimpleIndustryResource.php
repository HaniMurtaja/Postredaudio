<?php

namespace App\Http\Resources\Industry;

use Illuminate\Http\Resources\Json\JsonResource;

class SimpleIndustryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
