<?php

namespace App\Http\Resources\ContentBlock\Layouts;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderLayoutResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'slide_width' => $this->getAttributes()['slide_width'] ?? '100',
            'slider_speed' => $this->getAttributes()['slider_speed'] ?? '15',
            'title_text_size' => $this->getAttributes()['title_text_size'] ?? 'medium',
            'slides' => array_map(function ($slide) {
                return [
                    'title' => $slide->attributes->title,
                    'header' => $slide->attributes->header,
                    'description' => $slide->attributes->description,
                    'audio' => isset($slide->attributes->audio) ? $slide->attributes->audio : false,
                    'image' => getSingleImageCollection($this->resource->getModel(), 'slide_media_' . $slide->key)
                ];
            }, $this->getAttributes()['slides'])
        ];
    }
}
