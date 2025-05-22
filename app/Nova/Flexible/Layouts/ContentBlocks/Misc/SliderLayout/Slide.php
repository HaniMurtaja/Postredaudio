<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\SliderLayout;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Flexible\Layouts\LayoutExtended;
use Spatie\MediaLibrary\HasMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;

class Slide extends LayoutExtended implements HasMedia
{
    use HasMediaLibrary;

    protected $name = 'slider_layout_slide';
    protected $title = 'Slide';
    protected $limit = 10;

    public function fields()
    {
        return [
            Media::make('Slide Media', 'slide_media')
                ->conversionOnDetailView('minified')
                ->conversionOnIndexView('minified')
                // ->conversionOnForm('minified')
                ->singleMediaRules('max:15000')
                ->rules(['required', 'max:1']),
            Text::make('Title', 'title')
                ->tooltip('Shown in the right lower corner of the slide.'),
            Trix::make('Header', 'header')
                ->tooltip('Shown in the topl left part of the slide.'),
            Trix::make('Description', 'description')
                ->tooltip('Text following the header.'),
            Boolean::make('Enable Audio', 'audio')
        ];
    }
}
