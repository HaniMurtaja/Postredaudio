<?php

namespace App\Nova;

use Laravel\Nova\Fields\Number;

class ProjectableFields
{
    public function __invoke($request, $relatedModel)
    {
        return [
            Number::make('sort_order'),
        ];
    }
}
