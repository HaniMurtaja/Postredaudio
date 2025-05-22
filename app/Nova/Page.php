<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;
use HolyMotors\InlineRelationships\InlineRelationships;
use Outl1ne\NovaSortable\Traits\HasSortableRows;
use Alexwenzel\DependencyContainer\HasDependencies;
use Alexwenzel\DependencyContainer\DependencyContainer;

class Page extends Resource
{
    use HasSortableRows;
    use HasDependencies;

    public static $model = \App\Models\Page::class;
    public static $search = ['name'];
    public function title()
    {
        return ucfirst($this->name);
    }

    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Name')
                ->creationRules('required', 'unique:pages,name')
                ->rules('required'),
            Select::make('Page Type', 'page_type')
                ->tooltip('
                    <span class="postred-red">Standard:</span> default page, built with content blocks.<br>
                    <span class="postred-red">Special:</span> page that doesn`t use standard content block structure.<br>
                    <span class="postred-red">External:</span> external page, that redirects outside of postredaduio.com. 
                ')
                ->rules(['required'])
                ->options([
                    0 => 'Standard',
                    1 => 'Special',
                    2 => 'External'
                ])->displayUsingLabels(),
            Text::make('Hash', 'hash')
                ->hideFromIndex()
                ->tooltip("
                    String to allow viewing of the disabled page".
                    (($this->slug && $this->hash) ? 
                    ":<br>
                    <a target='_blank' href='https://www.postredaudio.com/$this->slug?h=$this->hash'>https://www.postredaudio.com/$this->slug<span class='postred-red'>?h=$this->hash</span></a>" : "."))
                ->rules(['max:16']),
            DependencyContainer::make([
                Text::make('Slug')->readonly(),
            ])->dependsOnNot('page_type', 2),
            DependencyContainer::make([
                Text::make('External Link', 'external_link')->rules(['required'])
            ])->dependsOn('page_type', 2),
            Boolean::make('Active')
                ->default(true),
            DependencyContainer::make([
                InlineRelationships::make('Content Blocks', 'contentBlocks', inverseRelationshipName: 'resource')
                    ->tooltip(
                        'Elements that make up the content of the page.<br/>
                        Sort order can set by dragging individual blocks.'
                    )
                    ->excludeRelated()
                    ->hideFromIndex()
                    ->showCreateRelationButton()
            ])->dependsOn('page_type', 0),
            DependencyContainer::make([
                Text::make('Project Section Label', 'project_section_label')
                    ->hideFromIndex()
                    ->rules(['max:100'])
                    ->tooltip('Text displayed in the header when scrolling project section.'),
                InlineRelationships::make('projects', resource: 'App\Nova\Project', displayName: 'Displayed Projects', inverseRelationshipName: 'featuringPages')
                    ->hideFromIndex(),
            ])->dependsOn('page_type', 0),
            new Panel('Menu Label Options', $this->labels()),
        ];
    }

    public function labels()
    {
        return [
            Text::make('Label', 'menu_label')
                ->hideFromIndex()
                ->tooltip('Text displayed in the navigation menu.<br> If empty, name will be displayed instead.')
                ->rules(['max:100']),
            Select::make('Label Size', 'label_size')
                ->hideFromIndex()
                ->options([
                    'small' => 'Small',
                    'large' => 'Large',
                ]),
            Select::make('Label Weight', 'label_weight')
                ->hideFromIndex()
                ->options([
                    'light' => 'Light',
                    'medium' => 'Medium',
                    'bold' => 'Bold',
                ]),
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

    public function authorizedToDelete(Request $request)
    {
        if ($request->user() && $request->user()->admin) {
            return true;
        }
        return false;
    }

    public static function authorizedToCreate(Request $request)
    {
        return true;
    }
}
