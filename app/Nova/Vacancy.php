<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;

class Vacancy extends Resource
{
    public static $model = \App\Models\Vacancy::class;
    public static $search = ['title'];
    public function title()
    {
        return ucwords($this->title);
    }

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Title', 'title')
                ->rules(['required'])
                ->sortable(),
            Text::make('Slug', 'slug')
                ->tooltip(
                    'Auto generated from the vacancy <span class="postred-red">title</span>.<br/>
                    Used as a link to the view the vacancy on contact page.<br/>
                    No input required.'
                )
                ->readonly()
                ->hideFromIndex(),
            Boolean::make('Active')
                ->tooltip('Check to display the vacancy in contact page')
                ->default(true)
                ->sortable(),
            Trix::make('About', 'about'),
            Trix::make('Job Description', 'description'),
            Text::make('External Link', 'external_link')
                ->hideFromIndex(),
            Flexible::make('Responsibilities')
                ->addLayout('Responsibility', 'responsibility', [
                    Textarea::make('Description', 'description')->rules(['required']),
                ])->button('Add Responsibility')
                ->collapsed(),
            Flexible::make('Requirements')
                ->addLayout('Requirement', 'requirement', [
                    Textarea::make('Description', 'description')->rules(['required']),
                ])->button('Add Requirement')
                ->collapsed(),
            Flexible::make('Skills')
                ->addLayout('Skill', 'skill', [
                    Textarea::make('Description', 'description')->rules(['required'])
                ])->button('Add Skill')
                ->collapsed(),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [
            new Filters\ActiveVacancyFilter
        ];
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
