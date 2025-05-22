<?php

namespace App\Http\Resources\ContentBlock\Layouts;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ClientResource;

class ClientLayoutResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'clients' => ClientResource::collection($this->resource->getModel()->clients),
        ];
    }
}
