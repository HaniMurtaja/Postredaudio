<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'logo' => getSingleImageCollection($this, 'logo')
        ];
    }
}
