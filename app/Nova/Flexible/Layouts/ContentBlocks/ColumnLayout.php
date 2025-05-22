<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks;

use App\Nova\Flexible\Layouts\LayoutExtended;
use App\Nova\Flexible\FlexibleExtended;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\ColumnLayout\Column;
use Spatie\MediaLibrary\HasMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;

class ColumnLayout extends LayoutExtended implements HasMedia
{
    use HasMediaLibrary;

    protected $name = 'column';
    protected $title = 'Column Layout';

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
                ->conversionOnForm('minified')
                ->singleMediaRules('max:15000')
                ->rules(['max:1']),
            FlexibleExtended::make('Columns', 'columns')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(Column::class)
                ->button('Add Column')
        ];
    }
}
