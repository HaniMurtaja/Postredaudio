<?php

namespace App\Http\Resources\ContentBlock\Layouts;

use Illuminate\Http\Resources\Json\JsonResource;

class ColumnLayoutResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'background_media' => getSingleImageCollection($this->resource->getModel(), 'background_media_' . $this->resource->key()),
            'columns' => array_map(function ($column) {
                return [
                    'title' => $column->attributes->title,
                    'text_primary' => $column->attributes->text_primary,
                    'text_secondary' => $column->attributes->text_secondary
                ];
            }, $this->resource['columns']),
        ];
    }
}
