<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\ColumnLayout;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use App\Nova\Flexible\Layouts\LayoutExtended;

class Column extends LayoutExtended
{
    protected $name = 'column_layout_column';
    protected $title = 'Column';

    public function fields()
    {
        return [
            Text::make('Title', 'title'),
            Trix::make('Primary Text', 'text_primary'),
            Trix::make('Secondary Text', 'text_secondary'),
        ];
    }
}
