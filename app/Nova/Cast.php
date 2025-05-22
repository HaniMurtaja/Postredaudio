<?php

namespace App\Nova;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Nova;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsToMany;

class Cast extends Resource
{
    public static $model = \App\Models\Cast::class;
    public static $title = 'name';
    public static $search = ['name', 'position', 'projects.title'];
    public static function label()
    {
        return 'Additional Project Info';
    }
    public function title()
    {
        $keyRole = $this->key_role ? ' - Key Info' : '';

        return "$this->name - $this->position$keyRole";
    }

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Title', 'name')
                ->rules(['required'])
                ->showWhenPeeking()
                ->showOnPreview(),
            Text::make('Info', 'position')
                ->rules(['required'])
                ->showWhenPeeking()
                ->showOnPreview(),
            Boolean::make('Key Info', 'key_role')
                ->default(false)
                ->tooltip(
                    'If checked, info item will also be shown separately from other info items.'
                )
                ->filterable()
                ->showWhenPeeking()
                ->showOnPreview(),
            Boolean::make('Show in List', 'show_in_list')
                ->default(true)
                ->tooltip(
                    'Will be shown in the additional info list of the project`s detail view page.'
                )
                ->showWhenPeeking()
                ->showOnPreview(),
            Boolean::make('Show in Thumbnail', 'show_in_thumbnail')
                ->default(false)
                ->tooltip(
                    'Will be shown in project preview popup window.'
                )
                ->showWhenPeeking()
                ->showOnPreview(),
            BelongsToMany::make('Projects')
                ->nullable()
                ->filterable()
                ->hideFromIndex()
                ->hideWhenCreating(),
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

    public static function uriKey()
    {
        return 'cast';
    }
}
