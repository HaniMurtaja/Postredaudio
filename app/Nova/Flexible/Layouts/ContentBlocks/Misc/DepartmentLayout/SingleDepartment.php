<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\DepartmentLayout;

use App\Models\Department;
use Laravel\Nova\Fields\Select;
use App\Nova\Flexible\Layouts\LayoutExtended;
use Spatie\MediaLibrary\HasMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;

class SingleDepartment extends LayoutExtended implements HasMedia
{
    use HasMediaLibrary;

    protected $name = 'department_layout_single_department';
    protected $title = 'Single Department';
    protected $departments;

    public function __construct($title = null, $name = null, $fields = null, $key = null, $attributes = [], callable $removeCallbackMethod = null, $model = null)
    {
        $this->departments = Department::whereHas('teamMembers')->get()->pluck('name', 'id')->toArray();

        parent::__construct($title, $name, $fields, $key, $attributes, $removeCallbackMethod, $model);
    }

    public function fields()
    {
        return [
            Select::make('Department')
                ->options($this->departments),
        ];
    }
}
