<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Flexible\Presets\ContentBlockPreset;
use App\Nova\Flexible\FlexibleExtended;
use Laravel\Nova\Nova;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\CallToActionItem;

class ContentBlock extends Resource
{
    public static $model = \App\Models\ContentBlock::class;
    public static $search = ['id', 'content', 'section_name'];
    public static $orderBy = ['sort_order' => 'asc'];
    public function title()
    {
        $hasContent = $this->toFlexible($this->content)->count();
        $layoutName = ($hasContent ? $this->toFlexible($this->content)->first()->name() : 'No Layout') . ($this->section_name ? (' - ' . $this->section_name) : '');

        return $this->id . ($layoutName ? (' - ' . ucwords(str_replace('_', ' ', $layoutName))) : '');
    }

    protected $linkableHeaders = [];
    protected $parentModel = null;
    protected $siblingContentBlocks;

    public function __construct($resource = null)
    {
        parent::__construct($resource);

        $this->siblingContentBlocks = collect($this->resource);

        $this->getParentModel();
        $this->getSiblingHeaders();
    }

    protected function getParentModel()
    {
        $this->parentModel = $this->model()->resource ?? null;
    }

    protected function getSiblingHeaders()
    {
        if ($this->parentModel) {
            $this->siblingContentBlocks = $this->parentModel->contentBlocks->where('id', '!=', $this->model()->id);

            if ($this->siblingContentBlocks->count()) {
                foreach ($this->siblingContentBlocks as $contentBlock) {
                    if ($contentBlock->content && $this->toFlexible($contentBlock->content)->count()) {
                        if ($this->toFlexible($contentBlock->content)[0]->name() === 'header') {
                            foreach ($this->toFlexible($contentBlock->content)[0]->getAttributes()['headers'] as $header) {
                                if ($header->attributes->header_text) {
                                    $this->linkableHeaders[] = [
                                        'key' => $header->key,
                                        'text' => $header->attributes->header_text,
                                    ];
                                }

                                if ($header->attributes->subheaders) {
                                    foreach ($header->attributes->subheaders as $subheader) {
                                        $this->linkableHeaders[] = [
                                            'key' => $subheader->key,
                                            'text' => $subheader->attributes->subheader_text
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function fields(NovaRequest $request)
    {
        return [
            Number::make('Position', 'sort_order')
                ->onlyOnIndex()
                ->sortable(),
            Text::make('Resource', function () {
                if (!$this->resource_type || !$this->resource_id) {
                    return '—';
                }

                $resourceId = $this->resource_id;
                $modelName = str_replace("App\Models\\", "", $this->resource_type);
                $resourceType = "\App\Nova\\$modelName";
                $modelInstanceTitle = ucfirst($this->resource_type::findOrFail($resourceId)->name);
                $url = Nova::path() . "/resources/" . $resourceType::uriKey() . "/{$resourceId}/edit";

                return "<a class=\"dim hover-underline text-primary font-bold\" href=\"{$url}\">$modelName: $modelInstanceTitle</a>";
            })->asHtml(),
            MorphTo::make('Resource')
                ->tooltip(
                    'The resource, this content block belongs to.<br/>
                    Can be an instance of: a <span class="postred-red">service</span>, an <span class="postred-red">industry</span> or a <span class="postred-red">page</span>.'
                )
                ->types([
                    Industry::class,
                    Service::class,
                    Page::class,
                ])
                ->hideFromIndex()
                ->nullable(),
            Boolean::make('Active')
                ->tooltip('Check to make the content block visible')
                ->filterable()
                ->default(true),
            Text::make('Content Type', 'content')
                ->onlyOnIndex()
                ->displayUsing(function ($value) {
                    $content =  $this->toFlexible($value)->first();

                    return $content ? ucwords(str_replace('_', ' ', $content->name())) : '—';
                }),
            Text::make('Section Name', 'section_name')
                ->tooltip('Title, displayed when this content block is scrolled to.')
                ->nullable()
                ->displayUsing(function ($value) {
                    return $value ? ucwords($value) : '';
                }),
            BelongsTo::make('Color Scheme', 'colorScheme')
                ->tooltip(
                    'Base color for this content block, sets the default background color.<br/>
                    Font colors are changed accordingly, unless specified.'
                ),
            Boolean::make('Show Scroll Down Button', 'scroll_down')
                ->tooltip('If checked, a scroll down button will be displayed inside this content block.')
                ->hideFromIndex(),
            Select::make('Linked Header', 'header_key')
                ->tooltip(
                    'If the resource this content block is attached to has a<br/>
                    <span class="postred-red">header</span> type content block,<br/>
                    this content block can be linked to that header.<br/>
                    When clicked on the header linked to the content block, it will scroll to that content block.'
                )
                ->hideFromIndex()
                ->hide()
                ->dependsOn('anonymous', function ($field) {
                    if ($this->linkableHeaders) {
                        $field->show()
                            ->options(array_merge(...array_map(fn ($header) => [$header['key'] => $header['text']], $this->linkableHeaders)));
                    }
                }),
            FlexibleExtended::make('Call to Action Buttons', 'links')
                ->fullWidth()
                ->collapsed()
                ->limit(3)
                ->hideFromIndex()
                ->addSingleLayout(CallToActionItem::class)
                ->button('Add Action Button'),
            FlexibleExtended::make('Layout', 'content', model: $this->model())
                ->tooltip(
                    'The actual content of the block. Can have one of several layout types:<br/>
                    <span class="postred-red">Header:</span> Each header item in header layout can be linked to a different<br/>
                    content block from the same resource. To link a header to a content block,<br/>
                    select the header item from the content block\'s <span class="postred-red">linked header</span> field.<br/>
                    <span class="postred-red">General:</span> Can have header text, paragraphs and/or gallery items.<br/>
                    <span class="postred-red">Text:</span> Two Columns. Large text on the left. Paragraphs on the right.<br/>
                    <span class="postred-red">Department:</span> Select one or multiple departments to show its <span class="postred-red">team members</span>.<br>
                    If only 1 department is selected, it will be rendered as a slider, else like a list.<br/>
                    <span class="postred-red">Testimonial:</span> Select one or multiple testimonials.<br/>
                    <span class="postred-red">Switch:</span> Displays the tuning knob animation. Has four three-field text fields.<br/>
                    <span class="postred-red">Slider:</span> Slide can be half or full width. Each slide has three optional text fields.<br/>
                    <span class="postred-red">Column:</span> Column-only layout. Each column has three text fields.<br/>
                    <span class="postred-red">Client:</span> Displays the carousel containing the <span class="postred-red">clients</span>. No input needed.<br/>'
                )
                ->preset(ContentBlockPreset::class),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [
            new Filters\ContentBlockResourceFilter,
            new Filters\ContentBlockTypeFilter
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

    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        if ($request->viaResource) {
            return "/resources/{$request->viaResource}/{$request->viaResourceId}/edit";
        }

        return "//resources/" . static::uriKey() . '/' . $resource->getKey() . '/edit';
    }
}
