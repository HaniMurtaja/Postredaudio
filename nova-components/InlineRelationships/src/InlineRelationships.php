<?php

namespace HolyMotors\InlineRelationships;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Fields\Tag;
use Laravel\Nova\Util;
use HolyMotors\InlineRelationships\SaveRelationshipService;

class InlineRelationships extends Tag
{
    public $component = 'inline-relationships';

    public $style = 'list';

    public $withPreview = true;

    public $preload = true;

    public $excludeRelated = false;

    public $inverseRelationshipName;

    public $attachableResourceName;

    public function __construct($name, $attribute = null, $resource = null, $displayName = null, $inverseRelationshipName = null)
    {
        parent::__construct($name, $attribute, $resource);
        $this->inverseRelationshipName = $inverseRelationshipName;
        $this->model = app(NovaRequest::class)->findModel();
        $this->attachableResourceName = method_exists($this->model, $this->attribute) ? get_class($this->model->{$this->attribute}()->getRelated()) : null;

        if ($displayName) {
            $this->name = $displayName;
        }
    }

    /**
     * Hide resources already attach to other model instances
     * 
     */
    public function excludeRelated()
    {
        $this->excludeRelated = true;

        return $this;
    }

    public function withoutPreview()
    {
        $this->withPreview = false;

        return $this;
    }

    /**
     * Get attachable resources excluding the already attached
     * to the model instance. If excludeRelated is enabled, also 
     * exclude resources attached to other model instancces.
     * 
     */
    protected function getAttachableResources()
    {
        if (!method_exists($this->model, $this->attribute)) return [];

        $excludedIds = [];

        if ($this->excludeRelated) {
            $excludedIds = ($this->attachableResourceName)::has($this->inverseRelationshipName)
                ->get()
                ->pluck('id')
                ->toArray();
        } else if ($this->model->exists) {
            $excludedIds = $this->model->{$this->attribute}->pluck('id')->toArray();
        }

        return $this->resolveAttributeValue(($this->attachableResourceName)::whereNotIn('id', $excludedIds)->get());
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return array
     */
    protected function resolveAttributeValue($attributeValue)
    {
        return $attributeValue->map(function ($model) {
            return $this->transformResult(
                app(NovaRequest::class),
                Nova::newResourceFromModel($model)
            );
        })->values()->all();
    }

    protected function transformResult(NovaRequest $request, $resource)
    {
        return array_filter([
            'avatar' => $resource->resolveAvatarUrl($request),
            'display' => (string) $resource->title(),
            'subtitle' => $resource->subtitle(),
            'value' => Util::safeInt($resource->getKey()),
            'sort_order' => isset($resource->pivot) && isset($resource->pivot->sort_order) ? ($resource->pivot->sort_order ?? 0) : ($resource->sort_order ?? 0),
        ]);
    }

    /**
     * Pass data to vue component
     * 
     */
    public function jsonSerialize(): array
    {
        /** @phpstan-ignore-next-line */
        return with(app(NovaRequest::class), function ($request) {
            return array_merge([
                'attachableResources' => $this->getAttachableResources(),
                'attachableResourceName' => basename(str_replace('\\', DIRECTORY_SEPARATOR, $this->attachableResourceName)),
                'relationship' => $this->attribute,
            ], parent::jsonSerialize());
        });
    }

    /**
     * Attach selected elements to the resource instance.
     * 
     */
    protected function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        return function () use ($model, $attribute, $request, $requestAttribute) {
            $relationshipValues = json_decode($request->{$requestAttribute}, true) ?? [];

            app(SaveRelationshipService::class)->attachToModel($model, $requestAttribute, $relationshipValues);
        };
    }
}
