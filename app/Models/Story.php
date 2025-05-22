<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Pivot\Testimoniable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Pivot\Projectable;

class Story extends Model implements HasMedia, Sortable
{
    use HasFactory;
    use InteractsWithMedia;
    use SortableTrait;

    protected $casts = [
        'date' => 'date',
        'main_content' => FlexibleCast::class
    ];
    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];
    protected $with = [
        'media'
    ];

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('featured', true);
    }

    public function scopeShowInGallery(Builder $query): void
    {
        $query->where('show_in_gallery', true);
    }

    public function homeGalleryItem(): MorphOne
    {
        return $this->morphOne(HomeGalleryItem::class, 'home_gallery_items', 'resource_type', 'resource_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'story_service');
    }

    public function activeServices(): BelongsToMany
    {
        return $this->services()->active();
    }

    public function recentStories()
    {
        return self::whereNot('id', $this->id)->with('services')->orderBy('date', 'desc')->limit(5)->get();
    }

    public function projects(): MorphToMany
    {
        return $this->morphToMany(Project::class, 'projectable')->using(Projectable::class)->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function activeProjects(): MorphToMany
    {
        return $this->projects()->active();
    }

    public function testimonials(): MorphToMany
    {
        return $this->morphToMany(Testimonial::class, 'testimoniable')->using(Testimoniable::class)->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function activeTestimonials(): MorphToMany
    {
        return $this->testimonials()->active();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover_image')->singleFile();
        $this->addMediaCollection('cover_image_mobile')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('minified')
            ->width(20);

        $this->addMediaConversion('320')
            ->width(320)
            ->performOnCollections('cover_image', 'cover_image_mobile');
    }

    static public function stories($draft = false)
    {
        return self::orderBy('featured', 'desc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('date', 'desc')
            ->when(!$draft, fn (Builder $query) => $query->active()->with(["activeServices"]))
            ->when($draft, fn (Builder $query) => $query->with(["services"]))
            ->simplePaginate(15);
    }
    static public function story($slug, $draft = false)
    {
        $story = self::where('slug', $slug)
            ->when(!$draft, fn (Builder $query) => $query->active()->with(['activeServices', 'activeProjects', 'activeTestimonials']))
            ->when($draft, fn (Builder $query) => $query->with(['services', 'projects', 'testimonials']))
            ->firstOrFail();
        $story->recent_stories = $story->recentStories();

        return $story;
    }
}
