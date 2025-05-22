<?php

namespace App\Nova\Flexible\Presets;

use App\Nova\Flexible\FlexibleExtended;

abstract class Preset
{
    /**
     * Execute the preset configuration
     *
     * @return void
     */
    abstract public function handle(FlexibleExtended $field);
}
