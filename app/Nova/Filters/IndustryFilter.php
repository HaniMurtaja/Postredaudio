<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Industry;

class IndustryFilter extends Filter
{
    public $name = 'Industries';

    public function apply(NovaRequest $request, $query, $value)
    {
        if($value){
            return $query->whereHas('industry', function($q) use ($value){
                $q->where('slug', $value);
            });
        }

        return $query;
    }

    public function options(NovaRequest $request)
    {
        return Industry::pluck('slug', 'name')->toArray();
    }
}
