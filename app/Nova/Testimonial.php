<?php

namespace App\Nova;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Tag;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\LinkItem;
use Laravel\Nova\Panel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Testimonial extends Resource
{
    use HasSortableRows;

    public static $model = \App\Models\Testimonial::class;
    public static $title = 'name';
    public static $search = ['name', 'profession', 'text'];

    public function fields(NovaRequest $request)
    {
        return [
            Boolean::make('Active', 'active')
                ->tooltip('Only active testimonials are displayed on the website.')
                ->default(true)
                ->filterable(),
            Text::make('Name', 'name')
                ->rules(['required'])
                ->showWhenPeeking()
                ->showOnPreview(),
            Text::make('Profession', 'profession')
                ->rules(['required'])
                ->showOnPreview()
                ->showWhenPeeking(),
            Trix::make('Text', 'text')
                ->rules(['required']),
            FlexibleExtended::make('Links', 'links')
                ->collapsed()
                ->addSingleLayout(LinkItem::class)
                ->button('Add Link Item'),
            BelongsTo::make('Client')
                ->tooltip('Select if the testimonail comes from a client.')
                ->nullable()
                ->filterable(),
            Tag::make('Projects')
                ->tooltip('The projects in which the reviewer collaborated.')
                ->preload()
                ->hideFromIndex()
                ->displayAsList()
                ->withPreview(),
            Tag::make('Achievements', 'achievements')
                ->tooltip('Reviewers awards.')
                ->preload()
                ->hideFromIndex()
                ->displayAsList()
                ->showCreateRelationButton(),
            Images::make('Image', 'image')
                ->tooltip(
                    'Reviewer\'s photo.</br>
                    Max size: 15 MB.<br/>
                    Allowed formats: <span class="postred-red">.jpg</span>, <span class="postred-red">.png</span>, <span class="postred-red">.avif</span>, <span class="postred-red">.webp</span>.'
                )
                ->setAllowedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/avif', 'image/webp'])
                ->rules(['required'])
                ->required()
                ->singleMediaRules('max:15000')
                ->showWhenPeeking()
                ->showOnPreview(),
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
