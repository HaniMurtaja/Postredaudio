<?php

namespace App\Http\Resources\ContentBlock\Layouts;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TestimonialResource;

class TestimonialLayoutResource extends JsonResource
{
    public function toArray($request)
    {
        if($this->resource->getModel()) {
            $testimonials = $this->resource->getModel()->testimonials;
        }else{
            $testimonials = [];
        }

        return [
            'title' => $this->getAttributes()['title'] ?? null,
            'testimonials' => TestimonialResource::collection($testimonials)
        ];
    }
}
