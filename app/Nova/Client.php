<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Client extends Resource
{
    use HasSortableRows;

    public static $model = \App\Models\Client::class;
    public static $title = 'name';
    public static $search = ['name'];
    public static function label()
    {
        return __('Clients');
    }

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Name', 'name')
                ->creationRules(['unique:clients,name'])
                ->rules(['required', 'max:100']),
            BelongsTo::make('Industry')
                ->rules(['required'])
                ->filterable(),
            Images::make('Logo', 'logo')
                ->tooltip(
                    'Max size: 15 MB.<br/>
                    Allowed formats: <span class="postred-red">.svg</span>, <span class="postred-red">.png</span>'
                )
                ->setAllowedFileTypes(['image/png', 'image/svg+xml'])
                ->singleMediaRules('max:15000')
                ->required()
                ->rules(['required']),
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
