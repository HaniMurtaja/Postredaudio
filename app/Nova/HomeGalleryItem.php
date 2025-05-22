<?php

namespace App\Nova;

use Laravel\Nova\Fields\Text;
use Outl1ne\NovaSortable\Traits\HasSortableRows;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use Laravel\Nova\Nova;

class HomeGalleryItem extends Resource
{
    use HasSortableRows;

    public static $model = \App\Models\HomeGalleryItem::class;
    public static $title = 'title';
    public static $clickAction = 'view';
    public static $globalSearchLink = 'view';
    public static $searchable = false;
    public static $perPageOptions = [200];

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Title', function () {
                $resourceId = $this->resource_id;
                $modelName = str_replace('App\Models\\', '', $this->resource_type);
                $resourceType = "\App\Nova\\$modelName";
                $url = Nova::path() . "/resources/" . $resourceType::uriKey() . "/{$resourceId}/edit";

                return "<a class=\"dim hover-underline text-primary font-bold\" href=\"{$url}\">$this->title</a>";
            })->asHtml(),
            Text::make('Type', 'resource_type')->displayUsing(function ($value) {
                return class_basename($value);
            })
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public function authorizedToView(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return true;
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
