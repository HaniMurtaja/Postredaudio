<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Pivot\Testimoniable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Testimonial extends Model implements HasMedia, Sortable
{
    use HasFactory;
    use InteractsWithMedia;
    use SortableTrait;

    protected $with = [
        'client',
        'projects',
        'achievements',
        'media'
    ];
    protected $casts = [
        'links' => FlexibleCast::class,
    ];
    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'testimonial_achievement');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function projects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, 'testimoniable')->using(Testimoniable::class)->withPivot('sort_order')->orderBy('sort_order');
    }

    public function stories(): MorphToMany
    {
        return $this->morphedByMany(Story::class, 'testimoniable')->using(Testimoniable::class)->withPivot('sort_order');
    }

    public function contentBlocks(): MorphToMany
    {
        return $this->morphedByMany(ContentBlock::class, 'testimoniable')->using(Testimoniable::class)->withPivot('sort_order');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('minified')
            ->width(20);
    }
}
