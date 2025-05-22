<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Flexible\Layouts\LayoutExtended;

class DisplayNameItem extends LayoutExtended
{
    protected $name = 'display_name_item';
    protected $title = 'Display Name Item';
    protected $limit = 4;

    public function fields()
    {
        return [
            Text::make('Text', 'text')
                ->rules(['required', 'max:20']),
            Boolean::make('New Line', 'new_line'),
            Select::make('Text Size', 'text_size')
                ->options([
                    'small' => 'Small',
                    'large' => 'Large',
                ])
                ->rules(['required']),
            Select::make('Text Color', 'text_color')
                ->options([
                    'red' => 'Red',
                    'white' => 'White',
                    'black' => 'Black',
                ])
                ->rules(['required']),
        ];
    }
}
