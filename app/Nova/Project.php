<?php

namespace App\Nova;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Tag;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use HolyMotors\ProjectModuleFilter\ProjectModuleFilter;
use HolyMotors\InlineRelationships\InlineRelationships;
use Alexwenzel\DependencyContainer\HasDependencies;
use Alexwenzel\DependencyContainer\DependencyContainer;
use Laravel\Nova\Fields\BelongsToMany;
use Outl1ne\NovaSortable\Traits\HasSortableRows;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\IframeItem;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\CallToActionItem;

class Project extends Resource
{
    use HasDependencies;
    use HasSortableRows;

    public static $model = \App\Models\Project::class;
    public static $title = 'title';
    public static $search = ['title', 'slug', 'industry.name', 'client.name', 'cast.name', 'cast.position'];
    public static $with = ['industry', 'client'];

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Title', 'title')
                ->rules(['required', 'max:100']),
            Text::make('Slug', 'slug')
                ->tooltip(
                    'Auto generated from the project <span class="postred-red">title</span>.<br/>
                    Used as a link to focus on the vacancy on contact page.<br/>
                    No input required.'
                )
                ->hideFromIndex()
                ->readonly(),
            Boolean::make('Active', 'active')
                ->tooltip('Only active projects are displayed on the website.')
                ->default(true)
                ->filterable(),
            Boolean::make('Featured', 'featured')
                ->tooltip('Featured project will be shown on the home page gallery.</br>At least <span class="postred-red">5</span> project must be featured.')
                ->default(1)
                ->filterable(),
            Boolean::make('Pinned', 'pinned')
                ->tooltip('Pinned project is displayed at the top of the projects page.')
                ->default(0)
                ->filterable(),
            DependencyContainer::make([
                ProjectModuleFilter::make('Pinned Module Layout', 'module_layout')
                    ->tooltip(
                        'If the project is <span class="postred-red">pinned</span>, it will display the selected modules in the top section of the projects page.<br/>
                        Click to toggle module visibility.<br/>
                        At least one module must be active.<br/>
                        Drag to reorder the position of active modules.'
                    )
                    ->hideFromIndex(),
            ])->dependsOn('pinned', true),
            Trix::make('Caption', 'caption')
                ->tooltip(
                    'Short project description.<br/>
                    Shown in the featured project page.'
                )
                ->rules(['max:600']),
            Trix::make('Description', 'description')
                ->tooltip(
                    'Full project description.<br/>
                    Shown in the project\'s details page.'
                ),
            BelongsTo::make('Industry')
                ->rules(['required'])
                ->filterable(),
            BelongsTo::make('Client')
                ->nullable()
                ->showCreateRelationButton()
                ->filterable(),
            Tag::make('Services', 'services')
                ->rules(['required'])
                ->preload()
                ->hideFromIndex()
                ->displayAsList()
                ->showCreateRelationButton(),
            InlineRelationships::make('cast', displayName: 'Additional Info', inverseRelationshipName: 'projects')
                ->tooltip(
                    'Misc info about the project, like people involved.<br/>
                    List can be sorted by dragging individual members.<br/>
                    Info items, with the <span class="postred-red">key info</span> selected, are also shown<br>
                    separately in the project\'s details page.'
                )
                ->hideFromIndex()
                ->showCreateRelationButton(),
            Tag::make('Achievements', 'achievements')
                ->preload()
                ->hideFromIndex()
                ->displayAsList()
                ->showCreateRelationButton(),
            InlineRelationships::make('testimonials', resource: 'App\Nova\Project', displayName: 'Testimonials', inverseRelationshipName: 'projects')
                ->hideFromIndex(),
            Text::make('Trailer URL', 'video_url')
                ->tooltip(
                    'Youtube or Vimeo link for the project\'s trailer.<br/>
                    Will be displayed in the project\'s details page.'
                )
                ->hideFromIndex(),
            FlexibleExtended::make('Iframes', 'iframes')
                ->fullWidth()
                ->collapsed()
                ->addSingleLayout(IframeItem::class)
                ->button('Add Iframe'),
            FlexibleExtended::make('Call to Action Buttons', 'links')
                ->fullWidth()
                ->collapsed()
                ->limit(3)
                ->addSingleLayout(CallToActionItem::class)
                ->button('Add Action Button'),
            new Panel('Media', $this->imageFields()),
        ];
    }

    public function imageFields()
    {
        return [
            Images::make('Cover Image', 'cover_image')
                ->tooltip(
                    'Project\'s cover photo. Will be displayed in the featured project<br/>
                    slider (if project is <span class="postred-red">featured</span>) and project\'s details page.<br/>
                    Max size: 15 MB.<br/>
                    Allowed formats: <span class="postred-red">.jpg</span>, <span class="postred-red">.png</span>, <span class="postred-red">.avif</span>, <span class="postred-red">.webp</span>.'
                )
                ->setAllowedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/avif', 'image/webp'])
                ->required()
                ->conversionOnDetailView('320')
                ->conversionOnIndexView('320')
                ->conversionOnForm('320')
                ->singleMediaRules('max:15000')
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
            Images::make('Secondary Image', 'secondary_image')
                ->tooltip(
                    'Project\'s secondary photo. If the project is <span class="postred-red">pinned</span>, it will be displayed<br/>
                    as the project\'s <span class="postred-red">Second Image</span> module in projects page.<br/>
                    Max size: 15 MB.<br/>
                    Allowed formats: <span class="postred-red">.jpg</span>, <span class="postred-red">.png</span>, <span class="postred-red">.avif</span>, <span class="postred-red">.webp</span>.'
                )
                ->setAllowedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/avif', 'image/webp'])
                ->hideFromIndex()
                ->conversionOnDetailView('320')
                ->conversionOnForm('320')
                ->croppable(false)
                ->singleMediaRules('max:15000')
                ->required()
                ->rules(['required']),
            Media::make('Video', 'video')
                ->tooltip(
                    'Short video. If provided, it will be displayed instead of <span class="postred-red">Cover Image</span> in project\'s details page.<br/>
                    Max size: 15 mb.<br/>
                    Allowed formats: <span class="postred-red">.mp4</span>, <span class="postred-red">.webm</span>.'
                )
                ->hideFromIndex()
                ->setAllowedFileTypes(['video/mp4', 'video/webm'])
                ->conversionOnDetailView('thumb')
                ->conversionOnForm('thumb')
                ->singleMediaRules('max:15000'),
            Media::make('Video - Mobile', 'video_mobile')
                ->tooltip(
                    'Vertical video for mobile layout.<br>
                    If not provided, original <span class="postred-red">video</span> will be used.'
                )
                ->hideFromIndex()
                ->setAllowedFileTypes(['video/mp4', 'video/webm'])
                ->conversionOnDetailView('thumb')
                ->conversionOnForm('thumb')
                ->singleMediaRules('max:15000')
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [
            new Filters\IndustryFilter,
            new Filters\ServiceBooleanFilter,
            new Filters\AchievementBooleanFilter,
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
