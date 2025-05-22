<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CastResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'position' => $this->position,
            'sort_order' => $this->sort_order,
        ];
    }
}
