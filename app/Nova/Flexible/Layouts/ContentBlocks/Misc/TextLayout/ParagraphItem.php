<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\TextLayout;

use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Select;
use App\Nova\Flexible\Layouts\LayoutExtended;

class ParagraphItem extends LayoutExtended
{
    protected $name = 'text_layout_paragraph_item';
    protected $title = 'Paragraph Item';

    public function fields()
    {
        return [
            Textarea::make('Label', 'label'),
            Select::make('Label Size', 'label_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Select::make('Label Color', 'label_color')
                ->options([
                    'red' => 'Red',
                    'black' => 'Black',
                    'white' => 'White',
                ]),
            Trix::make('Note', 'note'),
            Select::make('Note Size', 'note_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Select::make('Note Color', 'note_color')
                ->options([
                    'red' => 'Red',
                    'black' => 'Black',
                    'white' => 'White',
                ]),
        ];
    }
}
