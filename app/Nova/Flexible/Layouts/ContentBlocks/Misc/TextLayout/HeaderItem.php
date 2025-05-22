<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\TextLayout;

use App\Nova\Flexible\Layouts\LayoutExtended;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;

class HeaderItem extends LayoutExtended
{
    protected $name = 'text_layout_header_item';
    protected $title = 'Header Item';
    protected $limit = 10;

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
