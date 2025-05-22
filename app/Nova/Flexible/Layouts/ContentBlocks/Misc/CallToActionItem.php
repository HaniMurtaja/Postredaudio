<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use App\Nova\Flexible\Layouts\LayoutExtended;

class CallToActionItem extends LayoutExtended
{
    protected $name = 'call_to_action_item';
    protected $title = 'Action Item';

    public function fields()
    {
        return [
            Text::make('Link', 'url')
                ->rules(['required']),
            Text::make('Label', 'url_text'),
            Boolean::make('Square Action', 'square')
        ];
    }
}
