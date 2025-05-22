<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\HeaderLayout;

use Laravel\Nova\Fields\Text;
use App\Nova\Flexible\Layouts\LayoutExtended;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\HeaderLayout\Subheader;

class SingleHeader extends LayoutExtended
{
    protected $name = 'single_header';
    protected $title = 'Single Header';
    protected $limit = 5;

    public function fields()
    {
        return [
            Text::make('Header Text', 'header_text'),
            FlexibleExtended::make('Subheaders', 'subheaders', model: $this->model)
                ->tooltip(
                    'Smaller sized headers that are separated by slashes (/).<br/>
                    Displayed next to the main header.<br/>
                    Maximum of 5 can be added for each header.'
                )
                ->addSingleLayout(Subheader::class)
                ->limit(5)
                ->button('Add Subheader'),
        ];
    }
}
