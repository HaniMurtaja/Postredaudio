<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks;

use App\Models\Industry;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\LayoutExtended;
use Laravel\Nova\Fields\BooleanGroup;

class ClientLayout extends LayoutExtended
{
    protected $name = 'client';
    protected $title = 'Client Layout';

    public function fields()
    {
        $options = array_flip(Industry::pluck('id', 'name')->toArray());
        $defaultValue = array_map(function ($option) {
            return true;
        }, $options);

        return [
            BooleanGroup::make('Industries', 'industries')
                ->options($options)
                ->defaultIndustries($defaultValue)
                ->tooltip('Display <span class="postred-red">clients</span> belonging to the selected industries')
        ];
    }
}
