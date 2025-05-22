<?php

namespace HolyMotors\InlineRelationships\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ResourceSearchRequest;
use Laravel\Nova\Resource;
use Laravel\Nova\Util;
use Laravel\Nova\Nova;

class NewResourceController extends Controller
{
    /**
     * Get the resource's display name
     */
    public function __invoke($resourceName, $resourceId)
    {
        $modelInstance = ("App\Models\\$resourceName")::find($resourceId);

        $resourceTag = [
            'value' => $resourceId,
            'display' => (string) Nova::newResourceFromModel($modelInstance)->title(),
        ];

        return response()->json($resourceTag);
    }
}
