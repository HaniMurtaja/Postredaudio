<?php

namespace App\Http\Resources\ContentBlock\Layouts;

use Illuminate\Http\Resources\Json\JsonResource;

class TextLayoutResource extends JsonResource
{
    public function toArray($request)
    {
        $model = $this->resource->getModel();
        $defaultTextColor = $model->colorScheme->name === 'gray' ? 'black' : 'white';
        $leftColumn = isset($this->resource["left_column"][0]) ? $this->resource["left_column"][0]->getAttributes() : null;
        $rightColumn = isset($this->resource["right_column"][0]) ? $this->resource["right_column"][0] : null;

        return [
            'background_media' => getSingleImageCollection($model, 'background_media_'. $this->resource->key()),
            'images' => $model->getMedia('images_$key')->count() ? $model->getMedia('images_$key')->map(
                fn ($media) => $media->getFullUrl()
            ) : null,
            'left_column' => [
                'headers' => array_map(fn ($header) => $header->attributes, ($leftColumn ? $leftColumn['headers'] : [])),
                'header_position' => $leftColumn ? $leftColumn['header_position'] : 'left',
                'caption' => $leftColumn ? $leftColumn['caption'] : null,
                'caption_size' => $leftColumn ? $leftColumn['caption_size'] : 'medium',
                'caption_style' => $leftColumn ? $leftColumn['caption_style'] : 'medium',
                'description' => $leftColumn ? $leftColumn['description'] : null,
                'description_size' => $leftColumn ? $leftColumn['description_size'] : 'medium',
                'description_style' => $leftColumn ? $leftColumn['description_style'] : 'medium',
            ],
            'right_column' => [
                'paragraphs' => array_map(function ($paragraph) use ($defaultTextColor) {
                    $paragraphAttributes = $paragraph->attributes;

                    return [
                        'label' => $paragraphAttributes->label ?? null,
                        'label_size' => $paragraphAttributes->label_size ?? 'medium',
                        'label_color' => $paragraphAttributes->label_color ?? $defaultTextColor,
                        'note' => $paragraphAttributes->note ?? null,
                        'note_size' => $paragraphAttributes->note_size ?? 'medium',
                        'note_color' => $paragraphAttributes->note_color ?? $defaultTextColor
                    ];
                }, $rightColumn ? $rightColumn->paragraphs : [])
            ]
        ];
    }
}
