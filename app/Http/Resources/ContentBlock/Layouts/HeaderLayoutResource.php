<?php

namespace App\Http\Resources\ContentBlock\Layouts;

use Illuminate\Http\Resources\Json\JsonResource;

class HeaderLayoutResource extends JsonResource
{
    public function toArray($request)
    {
        $attributes = $this->resource->getAttributes();

        return [
            "background_media" => getSingleImageCollection($this->resource->getModel(), 'background_media_' . $this->resource->key()),
            "headers" => array_map(function ($header) {
                return [
                    'header_id' => $header->key,
                    'header_text' => $header->attributes->header_text,
                    'subheaders' => array_map(function ($subheader) {
                        return [
                            'subheader_id' => $subheader->key,
                            'subheader_text' => $subheader->attributes->subheader_text,
                        ];
                    }, $header->attributes->subheaders)
                ];
            }, $attributes['headers'])
        ];
    }
}
