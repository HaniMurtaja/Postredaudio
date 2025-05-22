<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Trix;
use App\Nova\Flexible\Layouts\LayoutExtended;

class Chapter extends LayoutExtended
{
    protected $name = 'general_layout_chapter';
    protected $title = 'Chapter';
    protected $limit = 1;

    public function fields()
    {
        return [
            Text::make('Title', 'title'),
            Select::make('Title Size', 'title_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Select::make('Title style', 'title_style')
                ->options([
                    'light' => 'Light',
                    'medium' => 'Medium',
                    'bold' => 'Bold',
                ]),
            Trix::make('Description', 'description'),
            Select::make('Description Size', 'description_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Select::make('Description style', 'description_style')
                ->options([
                    'light' => 'Light',
                    'medium' => 'Medium',
                    'bold' => 'Bold',
                ]),
        ];
    }
}
