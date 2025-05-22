<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Department;

class DepartmentFilter extends BooleanFilter
{
    public $name = 'Departments';

    public function apply(NovaRequest $request, $query, $value)
    {
        $departments = array_keys(array_filter($value, function($value){
            return $value;
        }));

        if($departments){
            return $query->whereHas('departments', function($q) use ($departments){
                $q->whereIn('id', $departments);
            }, '=', count($departments));
        }

        return $query;
    }

    public function options(NovaRequest $request)
    {
        return Department::whereHas('teamMembers')->pluck('id', 'name')->toArray();
    }
}
