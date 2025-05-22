<?php

namespace App\Nova\Flexible;

use Whitecube\NovaFlexibleContent\Layouts\LayoutInterface;
use Whitecube\NovaFlexibleContent\Flexible;

class FlexibleExtended extends Flexible
{
    public $modelInstance = null;

    /**
     * Create a fresh flexible field instance
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null, $model = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->modelInstance = $model;
    }

    public function addSingleLayout($layout)
    {
        if (is_string($layout) && is_a($layout, LayoutInterface::class, true)) {
            $layout = new $layout(model: $this->modelInstance);
        }

        if (!($layout instanceof LayoutInterface)) {
            throw new \Exception('Layout Class "' . get_class($layout) . '" does not implement LayoutInterface.');
        }

        $this->registerLayout($layout);

        return $this;
    }
}
