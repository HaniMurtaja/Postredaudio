<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks;

use App\Nova\Flexible\Layouts\LayoutExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\TextLayout\LeftColumn;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\TextLayout\RightColumn;
use Spatie\MediaLibrary\HasMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;
use App\Nova\Flexible\FlexibleExtended;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class TextLayout extends LayoutExtended implements HasMedia
{
    use HasMediaLibrary;

    protected $name = 'text';
    protected $title = 'Text Layout';
    protected $casts = [
        'left_column' => FlexibleCast::class,
        'right_column' => FlexibleCast::class
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
                // ->conversionOnDetailView('minified')
                // ->conversionOnIndexView('minified')
                // ->conversionOnForm('minified')
                ->singleMediaRules('max:15000')
                ->rules(['max:1']),
            FlexibleExtended::make('Left Column', 'left_column')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(LeftColumn::class)
                ->button('Insert Left Section'),
            FlexibleExtended::make('Right Column', 'right_column')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(RightColumn::class)
                ->button('Insert Right Section'),
            Media::make('Images', 'images')
                ->singleMediaRules('max:15000')
                ->rules(['max:6'])
                ->setAllowedFileTypes(['image/jpeg', 'image/jpg', 'image/avif', 'image/webp', 'image/png', 'image/svg+xml']),
        ];
    }
}
