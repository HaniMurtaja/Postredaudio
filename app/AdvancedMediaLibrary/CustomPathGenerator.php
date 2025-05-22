<?php

namespace App\AdvancedMediaLibrary;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomPathGenerator implements PathGenerator
{
    public $model;

    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getModelNameId($media);
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    { 
        return $this->getModelNameId($media);
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getModelNameId($media);
    }

    private function getModelNameId(Media $media) {
        $modelName = substr(strtolower($media->model_type), 11);
        return $this->model = $modelName.'/'.$media->model_id.'/'.$media->collection_name.'/'.$media->id.'/';
    }
}