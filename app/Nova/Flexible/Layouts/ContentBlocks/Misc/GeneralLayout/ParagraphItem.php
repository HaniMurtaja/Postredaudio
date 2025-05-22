<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\GeneralLayout;

use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Trix;
use App\Nova\Flexible\Layouts\LayoutExtended;
use Spatie\MediaLibrary\HasMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

class ParagraphItem extends LayoutExtended implements HasMedia
{
    use HasMediaLibrary;

    protected $name = 'general_layout_paragraph_item';
    protected $title = 'Paragraph Item';

    public function fields()
    {
        return [
            Images::make('Paragraph Image', 'paragraph_image')
                ->singleMediaRules('max:15000')
                ->rules(['max:1'])
                ->setAllowedFileTypes(['image/png', 'image/svg+xml']),
            Textarea::make('Label', 'label'),
            Select::make('Label Size', 'label_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Trix::make('Note', 'note'),
            Select::make('Note Size', 'note_size')
                ->options([
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                ]),
            Select::make('Note Style', 'note_style')
                ->options([
                    'light' => 'Light',
                    'medium' => 'Medium',
                    'bold' => 'Bold',
                ]),
        ];
    }
}
