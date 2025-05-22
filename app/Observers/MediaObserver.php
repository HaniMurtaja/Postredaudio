<?php

namespace App\Observers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Jobs\SaveBase64Image;

class MediaObserver
{
    public function created(Media $media)
    {
        SaveBase64Image::dispatch($media)->delay(now()->addMinutes(1));
    }

    public function deleted(Media $media)
    {
        deleteImageEntry($media);
    }
}
