<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Flexible\Layouts\LayoutExtended;
use Spatie\MediaLibrary\HasMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;

class GalleryItem extends LayoutExtended implements HasMedia
{
    use HasMediaLibrary;

    protected $name = 'general_layout_gallery_item';
    protected $title = 'Gallery Item';
    protected $limit = 10;

    public function fields()
    {
        return [
            Media::make('Gallery Media', 'gallery_media')
                // ->conversionOnDetailView('minified')
                // ->conversionOnIndexView('minified')
                // ->conversionOnForm('minified')
                ->singleMediaRules('max:15000')
                ->required()
                ->rules(['max:1', 'required']),
            Text::make('Title', 'title')
                ->tooltip('Text shown in the right lower<br/>corner of the gallery item.'),
            Boolean::make('Enable Audio', 'audio')
        ];
    }
}
