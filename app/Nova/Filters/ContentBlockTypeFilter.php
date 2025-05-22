<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Enums\LayoutType;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class ContentBlockTypeFilter extends Filter
{
    public $name = 'Content Type';

    public function apply(NovaRequest $request, $query, $value)
    {
        if($value){
            return $query->whereJsonContains('content', [['layout' => $value]])->get();
        }

        return $query;
    }

    public function options(NovaRequest $request)
    {
        return array_merge(...array_map(function($layoutType){
            return [ucwords($layoutType) => $layoutType];
        }, LayoutType::values()));
    }
}
