<?php

namespace App\Http\Resources\ContentBlock\Layouts;

use Illuminate\Http\Resources\Json\JsonResource;

class SwitchLayoutResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'switch_values' => array_map(fn ($switch) => $switch->attributes, $this->resource->getAttributes()['switch_values'])
        ];
    }
}
