<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Pivot\Projectable;

class Page extends Model implements Sortable, HasMedia
{
    use HasFactory;
    use SortableTrait;
    use InteractsWithMedia;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public $fillable = [
        'name',
        'page_type'
    ];

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function contentBlocks(): MorphMany
    {
        return $this->morphMany(ContentBlock::class, 'resource')->orderBy('sort_order');
    }

    public function activeContentBlocks(): MorphMany
    {
        return $this->contentBlocks()->active();
    }

    public function projects(): MorphToMany
    {
        return $this->morphToMany(Project::class, 'projectable')->using(Projectable::class)->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function activeProjects(): MorphToMany
    {
        return $this->projects()->active();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('minified')
            ->width(20);
    }

    static public function pages($draft = false, $hash = null)
    {
        return self::when($draft, fn (Builder $query) => $query)
            ->when(!$draft && $hash, fn(Builder $query) => $query->where('hash', $hash)->orWhere('active', 1))
            ->when(!$draft && !$hash, fn (Builder $query) => $query->active())
            ->orderBy('sort_order', 'asc')
            ->get();
    }
    
    static public function page($slug, $draft = false, $hash = null)
    {
        return self::when($draft, fn (Builder $query) => 
                $query->where('slug', $slug)
                    ->with(['contentBlocks', 'projects'])
            )->when(!$draft && $hash, fn(Builder $query) =>  
                $query->where([['slug', $slug],['hash', $hash]])
                    ->orWhere([['slug', $slug],['active', 1]])
                    ->with(['contentBlocks', 'projects'])
            )->when(!$draft && !$hash, fn(Builder $query) => 
                $query->where('slug', $slug)
                    ->active()
                    ->with(['activeContentBlocks', 'activeProjects']))
            ->firstOrFail();
    }
}
