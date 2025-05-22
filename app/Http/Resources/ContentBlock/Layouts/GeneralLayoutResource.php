<?php

namespace App\Http\Resources\ContentBlock\Layouts;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralLayoutResource extends JsonResource
{
    public function toArray($request)
    {
        $attributes = $this->resource->getAttributes();
        $model = $this->resource->getModel();
        $topSection = isset($attributes['top_section'][0]) ? $attributes['top_section'][0]->attributes : null;
        $header = isset($attributes['header'][0]) ? $attributes['header'][0]->attributes : null;
        $secondaryHeader = isset($attributes['secondary_header'][0]) ? $attributes['secondary_header'][0]->attributes : null;
        $chapter = isset($attributes['chapter'][0]) ? $attributes['chapter'][0]->attributes : null;
        $defaultTextColor = $model->colorScheme->name === 'gray' ? 'black' : 'white';

        return [
            'background_media' => getSingleImageCollection($model, 'background_media_' . $this->resource->key()),
            'top_section' => $topSection ? [
                'text' => $topSection->text,
                'text_size' => $topSection->text_size ?? 'fit',
                'text_color' => $topSection->text_color ?? $defaultTextColor,
            ] : null,
            'gallery_items' => array_map(function ($galleryItem) use ($model) {
                return [
                    'title' => $galleryItem->attributes->title,
                    'audio' => isset($galleryItem->attributes->audio) ? $galleryItem->attributes->audio : false,
                    'image' => getSingleImageCollection($model, 'gallery_media_' . $galleryItem->key)
                ];
            }, $attributes['gallery_items']),
            'gallery_title_size' => $attributes['gallery_title_size'] ?? 'medium',
            'gallery_speed' => $attributes['gallery_speed'] ?? '1',
            'text_section_width' => $attributes['text_section_width'] ?? '3/3',
            'header' => $header ? [
                'header_position' => $header->header_position ?? 'left',
                'title' => $header->title ?? null,
                'title_size' => $header->title_size ?? 'medium',
                'title_color' => $header->title_color ?? $defaultTextColor,
                'description' => $header->description ?? null,
                'description_size' => $header->description_size ?? 'medium',
                'description_color' => $header->description_color ?? $defaultTextColor,
            ] : null,
            'secondary_header' => $secondaryHeader ? [
                'text' => $secondaryHeader->text ?? null,
                'text_size' => $secondaryHeader->text_size ?? 'medium',
                'text_color' => $secondaryHeader->text_color ?? $defaultTextColor,
            ] : null,
            'chapter' => $chapter ? [
                'title' => $chapter->title ?? null,
                'title_size' => $chapter->title_size ?? 'medium',
                'title_style' => $chapter->title_style ?? $defaultTextColor,
                'description' => $chapter->description ?? null,
                'description_size' => $chapter->description_size ?? 'medium',
                'description_style' => $chapter->description_style ?? $defaultTextColor,
            ] : null,
            'paragraphs' => array_map(function ($paragraph) use ($model) {
                return [
                    'paragraph_image' => getSingleImageCollection($model, 'paragraph_image_' . $paragraph->key),
                    'label' => $paragraph->attributes->label ?? null,
                    'label_size' => $paragraph->attributes->label_size ?? 'medium',
                    'note' => $paragraph->attributes->note ?? null,
                    'note_size' => $paragraph->attributes->note_size ?? 'medium',
                    'note_style' => $paragraph->attributes->note_style ?? 'medium'
                ];
            }, $attributes['paragraphs'] ?? [])
        ];
    }
}
