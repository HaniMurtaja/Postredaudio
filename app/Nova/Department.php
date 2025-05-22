<?php

namespace App\Nova;

use Laravel\Nova\Fields\Text;
use App\Nova\Flexible\FlexibleExtended;
use Laravel\Nova\Http\Requests\NovaRequest;
use HolyMotors\InlineRelationships\InlineRelationships;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\DisplayNameItem;

class Department extends Resource
{
    public static $model = \App\Models\Department::class;
    public static $title = 'name';
    public static $search = ['name'];

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Department Name', 'name')
                ->rules(['required', 'max:100'])
                ->creationRules('unique:departments,name')
                ->updateRules('unique:departments,name,{{resourceId}}'),
            FlexibleExtended::make('Display Name', 'display_name')
                ->tooltip('If empty, department name will be rendered')
                ->addSingleLayout(DisplayNameItem::class)
                ->collapsed()
                ->button('Add Display Name Item'),
            InlineRelationships::make('Team Members', 'teamMembers', inverseRelationshipName: 'departments')
                ->tooltip(
                    '<span class="postred-red">Team members</span> that will belong to this department.<br/>
                Can have multiple team members.<br/>
                Department can then be selected in the <span class="postred-red">department layout</span> of a <span class="postred-red">content block</span>.'
                )
                ->hideFromIndex()
                ->showCreateRelationButton(),
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
}
