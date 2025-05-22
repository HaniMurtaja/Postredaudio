<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Select;
use App\Nova\Flexible\Layouts\LayoutExtended;

class Header extends LayoutExtended
{
    protected $name = 'general_layout_header';
    protected $title = 'Header';
    protected $limit = 1;

    public function fields()
    {
        return [
            Select::make('Header Position', 'header_position')
                ->options([
                    'top' => 'Top',
                    'left' => 'Left'
                ]),
            Text::make('Title', 'title'),
            Select::make('Title Size', 'title_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Select::make('Title Color', 'title_color')
                ->options([
                    'red' => 'Red',
                    'black' => 'Black',
                    'white' => 'White',
                ]),
            Trix::make('Description', 'description'),
            Select::make('Description Size', 'description_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Select::make('Description Color', 'description_color')
                ->options([
                    'red' => 'Red',
                    'black' => 'Black',
                    'white' => 'White',
                ]),
        ];
    }
}
