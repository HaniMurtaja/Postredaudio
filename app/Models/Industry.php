<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Pivot\Projectable;

class Industry extends Category
{
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->orderBy('sort_order');
    }

    public function activeProjects(): HasMany
    {
        return $this->projects()->active();
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
        return $this->projectables()->active();
    }

    static public function industries($draft = false)
    {
        return self::when(!$draft, fn (Builder $query) => $query->active()->with("activeProjects"))
            ->when($draft, fn (Builder $query) => $query->with("projects"))
            ->get();
    }

    static public function industry($slug, $draft = false)
    {
        return self::where('slug', $slug)
            ->when(!$draft, function (Builder $query) {
                return $query->active()->with(['activeContentBlocks', 'activeProjects', 'projectables']);
            })->when($draft, function (Builder $query) {
                return $query->with(['contentBlocks', 'projects', 'projectables']);
            })
            ->firstOrFail();
    }
}
