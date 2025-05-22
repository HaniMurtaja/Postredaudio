<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks;;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use App\Nova\Flexible\Layouts\LayoutExtended;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\SliderLayout\Slide;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class SliderLayout extends LayoutExtended
{
    protected $name = 'slider';
    protected $title = 'Slider Layout';
    protected $casts = [
        'slides' => FlexibleCast::class
    ];

    public function fields()
    {
        return [
            Select::make('Slide Width', 'slide_width')
                ->tooltip('How much horizontal space will the slider take.')
                ->options([
                    '100' => '100%',
                    '66' => '66%',
                    '50' => '50%',
                    '33' => '33%',
                ]),
            Number::make('Slide Change Speed', 'slider_speed')
                ->tooltip(
                    'Time between slide changes.<br>
                    Default: 15 second.'
                )
                ->defaultValue(1)
                ->min(0.01)
                ->max(120)
                ->step(0.01),
            Select::make('Slide Title Text Size', 'title_text_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            FlexibleExtended::make('Slides', 'slides')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(Slide::class)
                ->button('Add Slide')
        ];
    }
}
