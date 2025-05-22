<?php

namespace HolyMotors\InlineRelationships;

use Exception;
use Illuminate\Support\Arr;

class SaveRelationshipService
{
    protected $model;

    protected $relationshipName;

    protected $sortFieldName;

    protected $attachableValues;

    protected $relationshipTypeName;

    public function attachToModel($model, string $relationshipName, array $attachableValues = [])
    {
        $this->sortFieldName = 'sort_order';
        $this->model = $model;
        $this->relationshipName = $relationshipName;
        $this->attachableValues = $this->fillMissingSortOrders($attachableValues, $this->sortFieldName);
        $this->relationshipTypeName = $this->getRelationshipClassName($this->model, $this->relationshipName);

        $attachableModelName = get_class($this->model->{$this->relationshipName}()->getRelated());

        $this->detachRelationships($this->model, $this->relationshipName, $this->relationshipTypeName);

        try {
            switch ($this->relationshipTypeName) {
                case 'MorphMany':
                case 'HasMany':
                    $this->saveOneToMany($attachableModelName, $this->attachableValues);
                    break;
                case 'BelongsToMany':
                case 'MorphToMany':
                    $this->saveManyToMany($this->attachableValues);
                    break;
                case 'BelongsTo':
                    break;
                case 'MorphTo':
                    break;
                case 'BelongsTo':
                    break;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the name of the relationship type used between models
     */
    protected function getRelationshipClassName($model, $relationshipName, $fullPath = false)
    {
        if (method_exists($model, $relationshipName)) {
            $relationshipClass = get_class($model->{$relationshipName}());

            return $fullPath ? $relationshipClass : basename(str_replace('\\', DIRECTORY_SEPARATOR, $relationshipClass));
        }

        return false;
    }

    protected function saveOneToMany($attachableModelName, $attachableValues)
    {
        $attachedResources = (new $attachableModelName)::whereIn('id', array_map(fn ($attachable) => $attachable['value'], $attachableValues ?? []))->get();

        if ($attachedResources->count()) {
            $this->model->{$this->relationshipName}()->saveMany($attachedResources);
            $this->setOneToManySortOrder($attachedResources, $attachableValues, $this->sortFieldName);
        }
    }

    protected function saveManyToMany($attachedResources)
    {
        $mappedResource = [];

        foreach ($attachedResources as $resource) {
            $mappedResource[$resource['value']] = ['sort_order' => $resource['sort_order']];
        }

        $this->model->{$this->relationshipName}()->sync($mappedResource);
    }

    /**
     * Detach existing relationships
     */
    protected function detachRelationships($model, string $relationshipName, string $relationshipTypeName)
    {
        if ($relationshipTypeName === 'MorphMany') {
            $model->{$relationshipName}()->update([
                $model->{$relationshipName}()->getMorphType() => null,
                $model->{$relationshipName}()->getForeignKeyName() => null,
                // 'sort_order' => null,
            ]);
        } elseif ($relationshipTypeName === 'HasMany') {
            $model->{$relationshipName}()->update([
                $model->{$relationshipName}()->getForeignKeyName() => null,
                // 'sort_order' => null,
            ]);
        }
    }

    /**
     * Set the order values for the attached resources.
     * Fill the missing values with the next highest sorting order.
     * If no sort order values are provided, it will start from 0.
     */
    protected function setOneToManySortOrder($collection, $attachableValues, $sortField)
    {
        if (array_key_exists($sortField, $collection[0]->getAttributes())) {
            try {
                foreach ($collection as $attachableResource) {
                    $index = array_search($attachableResource->id, array_column($this->attachableValues, 'value'));
                    $attachableResource->{$sortField} = $attachableValues[$index][$sortField];
                    $attachableResource->save();
                }
            } catch (Exception $e) {
                throw $e->getMessage();
            }
        }
    }

    protected function fillMissingSortOrders(array $attachableValues, $sortField)
    {
        $nextOrderNumber = $this->getNextOrder($attachableValues);

        return array_map(function ($attachment) use ($sortField, &$nextOrderNumber) {
            $attachment[$sortField] = (array_key_exists($sortField, $attachment) &&
                $attachment[$sortField] > 0) ? $attachment[$sortField] : $nextOrderNumber++;

            return $attachment;
        }, $attachableValues);
    }

    /**
     * Get the first available sort order number from $attachableValues
     * If no attachable has a set sort_order, set it to 1
     */
    protected function getNextOrder(array $attachableValues = [])
    {
        if (count($attachableValues)) {
            $sortOrders = array_column($attachableValues, 'sort_order');

            return $sortOrders ? max($sortOrders) + 1 : 1;
        }

        return 1;
    }
}
