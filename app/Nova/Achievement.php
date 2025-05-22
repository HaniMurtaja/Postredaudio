<?php

namespace App\Nova;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

class Achievement extends Resource
{
    public static $model = \App\Models\Achievement::class;
    public static $title = 'name';
    public static $search = ['name'];

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Name', 'name')
                ->required()
                ->sortable(),
            Images::make('Logo', 'logo')
                ->tooltip(
                    'Max size: 15 MB.<br/>
                    Allowed formats: <span class="postred-red">.svg</span>, <span class="postred-red">.png</span>.'
                )
                ->setAllowedFileTypes(['image/png', 'image/svg+xml'])
                ->singleMediaRules('max:15000')
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
}
