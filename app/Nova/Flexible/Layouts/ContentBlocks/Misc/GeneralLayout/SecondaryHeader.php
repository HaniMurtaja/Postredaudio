<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use App\Nova\Flexible\Layouts\LayoutExtended;

class SecondaryHeader extends LayoutExtended
{
    protected $name = 'general_layout_secondary_header';
    protected $title = 'Secondary Header';
    protected $limit = 1;

    public function fields()
    {
        return [
            Text::make('Text', 'text'),
            Select::make('Text Size', 'text_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Select::make('Text Color', 'text_color')
                ->options([
                    'red' => 'Red',
                    'black' => 'Black',
                    'white' => 'White',
                ]),
        ];
    }
}
