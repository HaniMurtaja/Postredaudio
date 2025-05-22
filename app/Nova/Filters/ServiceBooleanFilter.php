<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Service;

class ServiceBooleanFilter extends BooleanFilter
{
    public $name = 'Services';

    public function apply(NovaRequest $request, $query, $value)
    {
        $services = array_keys(array_filter($value, function($value){
            return $value;
        }));

        if($services){
            return $query->whereHas('services', function($q) use ($services){
                $q->whereIn('slug', $services);
            }, '=', count($services));
        }

        return $query;
    }

    public function options(NovaRequest $request)
    {
        return Service::pluck('slug', 'name')->toArray();
    }
}
