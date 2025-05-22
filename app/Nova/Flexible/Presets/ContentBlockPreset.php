<?php

namespace App\Nova\Flexible\Presets;

use App\Nova\Flexible\Layouts\ContentBlocks\HeaderLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\GeneralLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\TextLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\DepartmentLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\SwitchLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\SliderLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\ColumnLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\ClientLayout;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\TestimonialLayout;
use App\Nova\Flexible\Presets\Preset;

class ContentBlockPreset extends Preset
{
    /**
     * Execute the preset configuration
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function handle(FlexibleExtended $field)
    {
        $field->button('Select Layout');
        $field->hideFromIndex();
        $field->showOnPreview();
        $field->limit(1);
        $field->addSingleLayout(HeaderLayout::class);
        $field->addSingleLayout(GeneralLayout::class);
        $field->addSingleLayout(TextLayout::class);
        $field->addSingleLayout(DepartmentLayout::class);
        $field->addSingleLayout(TestimonialLayout::class);
        $field->addSingleLayout(SwitchLayout::class);
        $field->addSingleLayout(SliderLayout::class);
        $field->addSingleLayout(ColumnLayout::class);
        $field->addSingleLayout(ClientLayout::class);
    }
}
