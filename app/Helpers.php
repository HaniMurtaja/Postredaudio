<?php

if (!function_exists('getSingleImageCollection')) {
    function getSingleImageCollection($model, string $collectionName, $conversions = [])
    {
        $media = $model->getMedia($collectionName)->count() ? $model->getFirstMedia($collectionName) : null;
        if (!$media) return null;

        $imagePath = $media->getFullUrl();
        $conversionsArray = [];

        foreach ($conversions as $conversion) {
            $conversionUrl = $media->getUrl($conversion);
            if ($conversionUrl) {
                $conversionsArray["conversion-$conversion"] = $conversionUrl;
            }
        }

        if ($conversionsArray) {
            return [
                'original' => $imagePath,
                ...$conversionsArray
            ];
        } else {
            return $imagePath;
        }
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 16)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('formatDateString')) {
    function formatDateString($date, $format = 'Y-m-d')
    {
        return is_a($date, 'DateTime') ? $date->format($format) : (new DateTime($date))->format($format);
    }
}

if (!function_exists('imageExists')) {
    function imageExists($imagePath, $disk = 'storage')
    {
        if ($imagePath) {
            return $imagePath ? \Illuminate\Support\Facades\Storage::disk($disk)->exists($imagePath) : false;
        }

        return false;
    }
}

if (!function_exists('generateContentBlocks')) {
    function generateContentBlocks($model, $contentBlocks = null)
    {
        if ($contentBlocks) {
            foreach ($contentBlocks as $index => $contentBlockData) {
                $contentBlock = \App\Models\ContentBlock::factory()
                    ->state([
                        'color_scheme_id' => \App\Models\ColorScheme::where('name', $contentBlockData['color_scheme_name'])->value('id'),
                        'section_name' => $contentBlockData['section_name'],
                        'header_key' => getHeaderKey($contentBlockData['linked_header_text'], $model->contentBlocks()),
                        'resource_id' => $model ? $model->id : null,
                        'resource_type' => $model ? get_class($model) : null,
                        'sort_order' => $model ? $index + 1 : null,
                    ])
                    ->layoutData($contentBlockData)
                    ->create();

                attachMediaToContentBlock($contentBlock);
            }
        }
    }
}

if (!function_exists('attachMediaToContentBlock')) {
    function attachMediaToContentBlock($contentBlock)
    {
        if (!isset($contentBlock->content[0])) return;

        $layoutData = $contentBlock->content[0];
        $layoutName = $layoutData->name();
        $layoutFields = $layoutData->getAttributes();

        if (in_array($layoutName, ['header', 'column', 'text', 'general']) && imageExists($layoutFields['background_media'])) {
            $contentBlock->addMedia(storage_path($layoutFields['background_media']))
                ->preservingOriginal()
                ->toMediaCollection("background_media_" . $layoutData->key());
        }

        if ($layoutName === 'slider') {
            foreach ($layoutFields['slides'] as $slide) {
                if (imageExists($slide->attributes->slide_media)) {
                    $contentBlock->addMedia(storage_path($slide->attributes->slide_media))
                        ->preservingOriginal()
                        ->toMediaCollection("slide_media_" . $slide->key);
                }
            }
        }

        if ($layoutName === 'text' && $layoutFields['images']) {
            $key = $layoutData->key();

            foreach ($layoutFields['images'] as $image)
                if (imageExists($image)) {
                    $contentBlock->addMedia(storage_path($image))
                        ->preservingOriginal()
                        ->toMediaCollection("images_$key");
                }
        }

        if ($layoutName === 'general') {
            if ($layoutFields['gallery_items']) {
                foreach ($layoutFields['gallery_items'] as $galleryItem) {
                    if (imageExists($galleryItem->attributes->gallery_media)) {
                        $contentBlock->addMedia(storage_path($galleryItem->attributes->gallery_media))
                            ->preservingOriginal()
                            ->toMediaCollection("gallery_media_" . $galleryItem->key);
                    }
                }
            }

            if ($layoutFields['paragraphs']) {
                foreach ($layoutFields['paragraphs'] as $paragraph) {
                    if (imageExists($paragraph->attributes->paragraph_image)) {
                        $contentBlock->addMedia(storage_path($paragraph->attributes->paragraph_image))
                            ->preservingOriginal()
                            ->toMediaCollection("paragraph_image_" . $paragraph->key);
                    }
                }
            }
        }
    }
}

if (!function_exists('getHeaderKey')) {
    function getHeaderKey(string $headerText = null, $contentBlocks)
    {
        if ($headerText) {
            $headerContentBlock = $contentBlocks->whereJsonContains('content', [['layout' => 'header']])->select('content')->first();

            if ($headerContentBlock) {
                $headerLayout = $headerContentBlock->content[0];

                if ($headerLayout) {
                    foreach ($headerLayout->getAttributes()['headers'] as $layout) {
                        if ($layout->attributes->header_text === $headerText) {
                            return $layout->key;
                        }

                        foreach ($layout->attributes->subheaders as $subheader) {
                            if ($subheader->attributes->subheader_text === $headerText) {
                                return $subheader->key;
                            }
                        }
                    }
                }
            }
        }

        return null;
    }
}

if (!function_exists('saveBase64Image')) {
    function saveBase64Image(\Spatie\MediaLibrary\MediaCollections\Models\Media $image)
    {
        if (!$image) return;

        $imageName = $image->file_name;
        $imageBase64 = $image->hasGeneratedConversion('minified') ? base64_encode(file_get_contents($image->getPath('minified'))) : null;

        if ($imageBase64) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists('base64.json')) {
                $images = json_decode(Illuminate\Support\Facades\Storage::disk('public')->get('base64.json'), true);
            } else {
                $images = [];
            }

            $images[$imageName] = $imageBase64;

            Illuminate\Support\Facades\Storage::put('public\base64.json', json_encode($images));
        }
    }
}

if (!function_exists('createFlexibleField')) {
    function createFlexibleField($data, $layoutName)
    {
        return json_encode(array_map(function ($layoutData) use ($layoutName) {
            return [
                'layout'      => $layoutName,
                'key'         => generateRandomString(),
                'attributes'  => $layoutData
            ];
        }, $data), true);
    }
}

if (!function_exists('deleteImageEntry')) {
    function deleteImageEntry(\Spatie\MediaLibrary\MediaCollections\Models\Media $image)
    {
        $imageName = $image->hasGeneratedConversion('minified') ? $image->file_name : null;

        if ($imageName && \Illuminate\Support\Facades\Storage::disk('public')->exists('base64.json')) {
            $images = json_decode(Illuminate\Support\Facades\Storage::disk('public')->get('base64.json'), true);

            unset($images[$imageName]);

            Illuminate\Support\Facades\Storage::put('public\base64.json', json_encode($images));
        }
    }
}

class HasFlexible
{
    use \Whitecube\NovaFlexibleContent\Concerns\HasFlexible;
}

if (!function_exists('flexibleStringValues')) {
    function flexibleStringValues($flexibleField)
    {
        $cast = (new HasFlexible)->cast($flexibleField);

        if (get_class($cast) === 'Whitecube\NovaFlexibleContent\Layouts\Collection') {
            return $cast = $cast->map(function ($flexValue) {
                return $flexValue->getAttributes();
            });
        }

        return [];
    }
}
