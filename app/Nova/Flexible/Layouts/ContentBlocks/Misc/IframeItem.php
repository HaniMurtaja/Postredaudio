<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use App\Nova\Flexible\Layouts\LayoutExtended;

class IframeItem extends LayoutExtended
{
    protected $name = 'iframe_item';
    protected $title = 'Iframe Item';
    protected $limit = 3;

    public function fields()
    {
        return [
            Text::make('Iframe Link', 'iframe')
                ->rules(['required', 'regex:/<iframe[^>]*src=["\']([^"\']+)["\'][^>]*><\/iframe>/i']),
            Text::make('Title', 'title')
                ->rules(['max:100']),
            Textarea::make('Description', 'description')
                ->rules(['max:255']),
        ];
    }
}
