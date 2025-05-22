<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\TextLayout;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use App\Nova\Flexible\Layouts\LayoutExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\TextLayout\ParagraphItem;
use App\Nova\Flexible\FlexibleExtended;

class RightColumn extends LayoutExtended
{
    protected $name = 'text_layout_right_column';
    protected $title = 'Right Column';
    protected $limit = 1;

    public function fields()
    {
        return [
            FlexibleExtended::make('Paragraphs', 'paragraphs')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(ParagraphItem::class)
                ->button('Insert a Paragraph'),
        ];
    }
}
