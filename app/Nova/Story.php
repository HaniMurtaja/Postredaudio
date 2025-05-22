<?php

namespace App\Nova;

use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Tag;
use Carbon\Carbon;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\Trix;
use Outl1ne\NovaSortable\Traits\HasSortableRows;
use HolyMotors\InlineRelationships\InlineRelationships;

class Story extends Resource
{
    use HasSortableRows;

    public static $model = \App\Models\Story::class;
    public static $title = 'title';
    public static $search = ['title', 'slug', 'date', 'cover_image_text', 'short_description', 'content'];

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Title', 'title')
                ->required(),
            Text::make('Slug', 'slug')
                ->hideFromIndex()
                ->readonly(),
            Boolean::make('Active', 'active')
                ->tooltip('Only active stories are displayed on the website.')
                ->default(true)
                ->filterable(),
            Boolean::make('Featured', 'featured')
                ->tooltip('Featured stories are shown at the top.')
                ->filterable()
                ->default(false),
            Boolean::make('Show in Gallery', 'show_in_gallery')
                ->tooltip('If checked, stories will be shown in the main gallery slider.')
                ->filterable()
                ->default(false),
            Date::make('Date', 'date')
                ->rules(['required'])
                ->default(Carbon::now()),
            Text::make('Author', 'author'),
            Select::make('Date/Author Position', 'date_author_position')
                ->options([
                    'left' => 'Left',
                    'right' => 'Right'
                ])
                ->hideFromIndex()
                ->default('left'),
            Trix::make('Description', 'description')
                ->tooltip('Short article description.'),
            Trix::make('Content', 'content')
                ->withFiles('public-stories')
                ->rules(['required']),
            Text::make('Project Section Label', 'project_section_label')
                ->hideFromIndex()
                ->rules(['max:100'])
                ->tooltip('Text displayed in the header when scrolling project section.'),
            InlineRelationships::make('projects', resource: 'App\Nova\Project', displayName: 'Displayed Projects', inverseRelationshipName: 'featuringStories')
                ->hideFromIndex(),
            Tag::make('Services', 'services')
                ->preload()
                ->hideFromIndex()
                ->displayAsList(),
            InlineRelationships::make('testimonials', resource: 'App\Nova\Project', displayName: 'Testimonials', inverseRelationshipName: 'stories')
                ->hideFromIndex(),
            Images::make('Cover Image', 'cover_image')
                ->setAllowedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/avif', 'image/webp'])
                ->singleMediaRules('max:15000')
                ->conversionOnDetailView('320')
                ->conversionOnIndexView('320')
                ->conversionOnForm('320')
                ->required()
                ->croppable(false)
                ->rules(['required']),
            Images::make('Cover Image - Mobile', 'cover_image_mobile')
                ->tooltip(
                    'Vertical image for mobile layout.<br>
                    If not provided, original <span class="postred-red">cover image</span> will be used.'
                )
                ->setAllowedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/avif', 'image/webp'])
                ->hideFromIndex()
                ->conversionOnDetailView('320')
                ->conversionOnForm('320')
                ->singleMediaRules('max:15000')
                ->croppable(false),
            Text::make('Cover Image Caption', 'cover_image_text')
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
