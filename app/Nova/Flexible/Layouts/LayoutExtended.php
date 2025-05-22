<?php

namespace App\Nova\Flexible\Layouts;

use Whitecube\NovaFlexibleContent\Layouts\Layout;

class LayoutExtended extends Layout
{

    public function __construct($title = null, $name = null, $fields = null, $key = null, $attributes = [], callable $removeCallbackMethod = null, $model = null)
    {
        if ($model) {
            $this->model = $model;
        }
        
        parent::__construct($title, $name, $fields, $key, $attributes, $removeCallbackMethod);
    }

    public function getModel()
    {
        return $this->model;
    }
}
