<?php

namespace App\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Boolean;
use Alexwenzel\DependencyContainer\HasDependencies;
use HolyMotors\InlineRelationships\InlineRelationships;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Service extends Resource
{
    use HasDependencies;
    use HasSortableRows;

    public static $model = \App\Models\Service::class;
    public static $title = 'name';
    public static $search = ['name'];

    public function fields(NovaRequest $request)
    {
        return [
            Hidden::make('Type', 'category_type_id')
                ->hideFromIndex()
                ->hideFromDetail()
                ->default($this->model()->type ? $this->model()->type->id : 1),
            Text::make('Name', 'name')
                ->rules(['required', 'max:100'])
                ->sortable(),
            Text::make('Slug', 'slug')
                ->tooltip(
                    'Auto generated from the service <span class="postred-red">name</span>.<br/>
                    Used as a link to the service\'s details page.<br/>
                    No input required.'
                )
                ->hideFromIndex()
                ->readonly(),
            Boolean::make('Active')
                ->tooltip('Check to display the service in the service list')
                ->default(true),
            InlineRelationships::make('Content Blocks', 'contentBlocks', inverseRelationshipName: 'resource')
                ->tooltip(
                    'Elements that make up the content of the page.<br/>
                    Sort order can set by dragging individual blocks.'
                )
                ->excludeRelated()
                ->hideFromIndex()
                ->showCreateRelationButton(),
            Text::make('Project Section Label', 'project_section_label')
                ->rules(['max:100'])
                ->tooltip('Text displayed in the header when scrolling project section')
                ->hideFromDetail(),
            InlineRelationships::make('projectables', resource: 'App\Nova\Project', displayName: 'Displayed Projects', inverseRelationshipName: 'featuringServices')
                ->hideFromIndex(),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        if ($request->viaResource) {
            return "/resources/{$request->viaResource}/{$request->viaResourceId}/edit";
        }

        return "//resources/" . static::uriKey() . '/' . $resource->getKey() . '/edit';
    }
}
