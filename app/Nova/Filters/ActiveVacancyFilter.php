<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ActiveVacancyFilter extends BooleanFilter
{
    public $name = 'Status';

    public function apply(NovaRequest $request, $query, $value)
    {
        return $value['active'] ? $query->active() : $query;
    }

    public function options(NovaRequest $request)
    {
        return ['Active' => 'active'];
    }

    public function default()
    {
        return ['active' => false];
    }
}
