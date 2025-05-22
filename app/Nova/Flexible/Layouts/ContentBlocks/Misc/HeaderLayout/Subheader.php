<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\HeaderLayout;

use Laravel\Nova\Fields\Text;
use App\Nova\Flexible\Layouts\LayoutExtended;

class Subheader extends LayoutExtended
{
    protected $name = 'single_subheader';
    protected $title = 'Single Subheader';
    protected $limit = 5;

    public function fields()
    {        
        return [
            Text::make('Subheader Text')
                ->fullWidth()
        ];
    }
}