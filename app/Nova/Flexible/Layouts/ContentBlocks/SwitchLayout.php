<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks;

use App\Nova\Flexible\Layouts\LayoutExtended;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\SwitchLayout\SwitchValue;

class SwitchLayout extends LayoutExtended
{
    protected $name = 'switch';
    protected $title = 'Switch Layout';

    public function fields()
    {
        return [
            FlexibleExtended::make('Switch Values', 'switch_values')
                ->collapsed()
                ->addSingleLayout(SwitchValue::class)
                ->button('Add Switch Value')
        ];
    }
}
