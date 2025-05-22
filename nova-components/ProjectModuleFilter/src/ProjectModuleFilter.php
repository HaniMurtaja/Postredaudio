<?php

namespace HolyMotors\ProjectModuleFilter;

use Laravel\Nova\Fields\Field;
use App\Models\Module;
use App\Models\Project;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Arr;
use App\Enums\ModuleType;

class ProjectModuleFilter extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'project-module-filter';

    /**
     * The project model instance
     * 
     * @var Project
     */
    public $model;

    /**
     * Key positions assigned to the project's cast
     * 
     */
    public $keyRoles;

    /**
     * Modules associated with the model instance
     * 
     */
    public $activeModules = [];

    /**
     * Selectable modules
     *
     */
    public $modules;

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->model = app(NovaRequest::class)->findModel();

        if (class_basename($this->model) === 'Project') {
            // Get key roles associated with the project
            $this->keyRoles = $this->model->keyCast()->count() ? array_keys($this->model->keyCast->groupBy('position')->toArray()) : [];
            $this->createMissingModules($this->keyRoles);
            $this->availableModuleNames = $this->getAvailableModuleNames($this->keyRoles);
            $this->removeUnavailableModules($this->model, $this->availableModuleNames);
            $this->activeModules = $this->getActiveModules($this->model);
            $this->modules = $this->getSelectableModules($this->availableModuleNames, $this->activeModules);
        }
    }

    /**
     * Attach returned mdoules to the project instance.
     * 
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $modulePosition = 1;
        $modules = array_map(function ($module) use (&$modulePosition) {
            $module['sort_order'] = $modulePosition;
            $modulePosition++;

            return $module;
        }, json_decode($request->input($requestAttribute) ?? "[]", true));

        if ($model->exists && $model->pinned) {
            $modules = array_map(function ($module) {
                unset($module['id']);

                return $module;
            }, Arr::keyBy($modules, 'id'));

            $model->modules()->sync($modules);
        }
    }

    /**
     * Get modules available to the project.
     * 
     */
    public function getSelectableModules($availableModuleNames, $selectedModules)
    {
        $assignedModuleCount = count($selectedModules);

        return Module::whereIn('name', $availableModuleNames)->get()->map(function ($module, $index) use (&$assignedModuleCount, $selectedModules) {
            $attachedModule = Arr::first($selectedModules, fn ($assignedModule) => $assignedModule['id'] === $module->id);
            if (!$attachedModule) $assignedModuleCount++;

            return [
                'id' => $module->id,
                'name' => $module->name,
                'sort_order' => $selectedModules ? ($attachedModule ? $attachedModule['sort_order'] : $assignedModuleCount) : $module->id,
                'active' => $selectedModules ? ($attachedModule ? true : false) : true
            ];
        })->sortBy('sort_order');
    }

    /**
     * If not found, create a new module from the project's key role.
     * 
     */
    public function createMissingModules(array $keyRoleNames)
    {
        foreach ($keyRoleNames as $keyRole) {
            if (!Module::where('name', $keyRole)->exists()) {
                Module::create(['name' => $keyRole]);
            }
        }
    }

    /**
     * Get module names that are available for selection.
     * 
     */
    public function getAvailableModuleNames(array $keyRoles)
    {
        $availableModules = [];

        array_push($availableModules, ...ModuleType::values(), ...$keyRoles);

        return $availableModules;
    }

    /**
     * Remove associated modules that are not default or from selected key role names.
     * 
     */
    public function removeUnavailableModules(Project $project, array $availableModuleNames)
    {
        $toDetach = $project->modules->filter(function ($module) use ($availableModuleNames) {
            return !in_array($module->name, $availableModuleNames);
        });

        $project->modules()->detach($toDetach);
    }

    /**
     * Get modules associated with the project instance.
     * 
     */
    public function getActiveModules(Project $model)
    {
        if ($model->pinned) {
            return $this->model
                ->modules()
                ->orderBy('sort_order')
                ->get()
                ->map(function ($activeModule, $index) {
                    return [
                        'id' => $activeModule->id,
                        'sort_order' => $index + 1
                    ];
                })
                ->toArray();
        } else {
            $model->modules()->detach();

            return [];
        }
    }

    public function jsonSerialize(): array
    {
        /** @phpstan-ignore-next-line */
        return with(app(NovaRequest::class), function ($request) {
            return array_merge([
                'modules' => (array) $this->modules->values()->toArray()
            ], parent::jsonSerialize());
        });
    }
}
