<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\TextLayout;

use App\Nova\Flexible\Layouts\LayoutExtended;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\TextLayout\HeaderItem;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Text;
use Spatie\MediaLibrary\HasMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;

class LeftColumn extends LayoutExtended implements HasMedia
{
    use HasMediaLibrary;

    protected $name = 'text_layout_left_column';
    protected $title = 'Left Column';
    protected $limit = 1;

    public function fields()
    {
        return [
            FlexibleExtended::make('Headers', 'headers')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(HeaderItem::class)
                ->button('Insert a Header'),
            Select::make('Header Position', 'header_position')
                ->options([
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ]),
            Text::make('Caption', 'caption'),
            Select::make('Caption Size', 'caption_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Select::make('Caption Style', 'caption_style')
                ->options([
                    'light' => 'Light',
                    'medium' => 'Medium',
                    'bold' => 'Bold',
                ]),
            Textarea::make('Description', 'description'),
            Select::make('Description Size', 'description_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Select::make('Description Style', 'description_style')
                ->options([
                    'light' => 'Light',
                    'medium' => 'Medium',
                    'bold' => 'Bold',
                ]),
        ];
    }
}
