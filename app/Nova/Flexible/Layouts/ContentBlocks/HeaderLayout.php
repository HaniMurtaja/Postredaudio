<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks;

use App\Nova\Flexible\Layouts\LayoutExtended;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\HeaderLayout\SingleHeader;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;
use Spatie\MediaLibrary\HasMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;

class HeaderLayout extends LayoutExtended implements HasMedia
{
    use HasMediaLibrary;

    protected $name = 'header';
    protected $title = 'Header Layout';
    protected $casts = [
        'headers' => FlexibleCast::class
    ];

    public function fields()
    {
        return [
            Media::make('Background Media', 'background_media')
                ->tooltip(
                    'Column\'s background image or video.<br/>
                    Max size: 15 MB.<br/>
                    Allowed formats: <span class="postred-red">.jpg</span>, <span class="postred-red">.png</span>, <span class="postred-red">.avif</span>, <span class="postred-red">.webp</span>, <span class="postred-red">.webm</span>, <span class="postred-red">.mp4</span>.'
                )
                ->setAllowedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/avif', 'image/webp', 'video/mp4', 'video/webm'])
                ->conversionOnDetailView('minified')
                ->conversionOnIndexView('minified')
                // ->conversionOnForm('minified')
                ->singleMediaRules('max:15000')
                ->rules(['max:1']),
            FlexibleExtended::make('Headers', 'headers', model: $this->model)
                ->fullWidth()
                ->button('Add Header')
                ->addSingleLayout(SingleHeader::class)
        ];
    }
}
