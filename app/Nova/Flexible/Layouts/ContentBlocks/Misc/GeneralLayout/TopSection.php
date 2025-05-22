<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use App\Nova\Flexible\Layouts\LayoutExtended;

class TopSection extends LayoutExtended
{
    protected $name = 'general_layout_top_section';
    protected $title = 'Top Section';
    protected $limit = 1;

    public function fields()
    {
        return [
            Text::make('Text', 'text'),
            Select::make('Text Size', 'text_size')
                ->options([
                    'fit' => 'Fit Width',
                    'small' => 'Small',
                    'medium' => 'Medium'
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
