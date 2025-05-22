<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks;

use App\Nova\Flexible\Layouts\LayoutExtended;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\DepartmentLayout\SingleDepartment;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class DepartmentLayout extends LayoutExtended
{
    protected $name = 'department';
    protected $title = 'Department Layout';

    public function fields()
    {
        return [
            FlexibleExtended::make('Departments', 'departments')
                ->fullWidth()
                ->addSingleLayout(SingleDepartment::class)
                ->button('Add Department'),
            Select::make('Style', 'style')
                ->options([
                    'standard' => 'Standard',
                    'executive' => 'Executive',
                ]),
            Text::make('Caption', 'caption')
                ->rules(['max:100'])
        ];
    }
}
