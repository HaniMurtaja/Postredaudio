<?php

namespace App\Nova\Filters;

use App\Models\Achievement;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class AchievementBooleanFilter extends BooleanFilter
{
    public $name = 'Achievements';
    
    public function apply(NovaRequest $request, $query, $value)
    {
        $achievements = array_keys(array_filter($value, function($value) {
            return $value;
        }));
        if($achievements){
            return $query->whereHas('achievements', function($q) use ($achievements) {
                $q->whereIn('name', $achievements);
            }, '=', count($achievements));
        }
        
        return $query;
    }

    public function options(NovaRequest $request)
    {
        return Achievement::pluck('name')->toArray();
    }
}
