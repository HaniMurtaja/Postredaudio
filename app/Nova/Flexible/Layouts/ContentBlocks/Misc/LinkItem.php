<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc;

use Laravel\Nova\Fields\Text;
use App\Nova\Flexible\Layouts\LayoutExtended;

class LinkItem extends LayoutExtended
{
    protected $name = 'link_item';
    protected $title = 'Link Item';

    public function fields()
    {
        return [
            Text::make('Link', 'url')
                ->rules(['required']),
            Text::make('Label', 'url_text')
        ];
    }
}
