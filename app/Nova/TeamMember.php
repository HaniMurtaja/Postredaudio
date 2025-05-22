<?php

namespace App\Nova;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\Trix;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\LinkItem;

class TeamMember extends Resource
{
    public static $model = \App\Models\TeamMember::class;
    public static $title = 'name';
    public static $search = ['name', 'position', 'bio'];

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Name', 'name')
                ->rules(['required'])
                ->showOnPreview()
                ->sortable(),
            Text::make('Position', 'position')
                ->showOnPreview()
                ->sortable(),
            Trix::make('Bio', 'bio')
                ->showOnPreview(),
            FlexibleExtended::make('Links', 'links')
                ->collapsed()
                ->addSingleLayout(LinkItem::class)
                ->button('Add Link Item'),
            Images::make('Photo', 'photo')
                ->tooltip(
                    'Team member\'s photo.</br>
                    Max size: 15 MB.<br/>
                    Allowed formats: <span class="postred-red">.jpg</span>, <span class="postred-red">.png</span>, <span class="postred-red">.avif</span>, <span class="postred-red">.webp</span>.'
                )
                ->showOnPreview()
                ->setAllowedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/avif', 'image/webp'])
                ->required()
                ->singleMediaRules('max:15000')
                ->rules(['required']),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [
            new Filters\DepartmentFilter
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
