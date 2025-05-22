<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Enums\ContentBlockResource;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class ContentBlockResourceFilter extends Filter
{
    public $name = 'Parent Resource';

    public function apply(NovaRequest $request, $query, $value)
    {
        if($value){
            $id = Str::before($value, "-");
            $model = Str::after($value, "-");

            return $query->whereHas('resource', function(Builder $query) use ($model, $id){
                $query->where('resource_type', $model)->where('resource_id', $id);
            })->get();
        }

        return $query;
    }

    public function options(NovaRequest $request)
    {
        return array_merge(...array_map(function($resourceCategory){
            return array_merge(...array_map(
                function($resource) use ($resourceCategory){
                    return [
                        $resourceCategory->name . ": " . Str::ucfirst($resource['name'] ?? $resource['title']) => $resource['id'] . "-" . $resourceCategory->value
                    ];
                },
                ($resourceCategory->value)::whereHas('contentBlocks')->get()->toArray()
            ));
        }, ContentBlockResource::cases()));
    }
}
