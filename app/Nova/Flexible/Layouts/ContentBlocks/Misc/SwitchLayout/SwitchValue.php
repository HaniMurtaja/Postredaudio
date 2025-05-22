<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\SwitchLayout;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use App\Nova\Flexible\Layouts\LayoutExtended;

class SwitchValue extends LayoutExtended
{
    protected $name = 'switch_layout_switch_value';
    protected $title = 'Switch Value';
    protected $limit = 4;

    public function fields()
    {
        return [
            Text::make('Name', 'name'),
            Text::make('Title', 'title'),
            Trix::make('Description', 'description'),
        ];
    }
}
