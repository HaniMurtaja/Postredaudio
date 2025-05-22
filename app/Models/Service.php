<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Pivot\Projectable;

class Service extends Category
{
    protected $with = [
        'projects',
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_service')->orderBy('sort_order');
    }

    public function activeProjects(): BelongsToMany
    {
        return $this->projects()->active();
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Story::class, 'story_service');
    }

    public function contentBlocks(): MorphMany
    {
        return $this->morphMany(ContentBlock::class, 'resource')->orderBy('sort_order');
    }

    public function activeContentBlocks(): MorphMany
    {
        return $this->contentBlocks()->active();
    }

    public function projectables(): MorphToMany
    {
        return $this->morphToMany(Project::class, 'projectable')->using(Projectable::class)->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function activeProjectables(): MorphToMany
    {
        return $this->projectables()->where('active');
    }

    static public function services($draft = false)
    {
        return self::when(!$draft, fn (Builder $query) => $query->active()->with('projects'))
            ->when($draft, fn (Builder $query) => $query->with('projects'))
            ->get();
    }

    static public function service($slug, $draft = false)
    {
        return self::where('slug', $slug)
            ->when(!$draft, function (Builder $query) {
                return $query->active()->with(['activeProjects', 'activeContentBlocks', 'projectables']);
            })->when($draft, function (Builder $query) {
                return $query->with(['projects', 'contentBlocks', 'projectables']);
            })
            ->firstOrFail();
    }
}
