<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Pivot\Testimoniable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;
use App\Models\Pivot\Projectable;

class Project extends Model implements HasMedia, Sortable
{
    use HasFactory;
    use InteractsWithMedia;
    use SortableTrait;

    protected $casts = [
        'iframes' => FlexibleCast::class,
    ];
    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];
    protected $with = ['achievements'];

    public function scopeFeatured(Builder $query): void
    {
        $query->where('featured', true);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function homeGalleryItem(): MorphOne
    {
        return $this->morphOne(HomeGalleryItem::class, 'home_gallery_items', 'resource_type', 'resource_id');
    }

    public function testimonials(): MorphToMany
    {
        return $this->morphToMany(Testimonial::class, 'testimoniable')
            ->using(Testimoniable::class)
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function activeTestimonials(): MorphToMany
    {
        return $this->testimonials()->active();
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function cast(): BelongsToMany
    {
        return $this->belongsToMany(Cast::class, 'project_cast')
            ->orderByPivot('sort_order');
    }

    public function listedCast(): BelongsToMany
    {
        return $this->cast()->where('show_in_list', true);
    }

    public function keyCast(): BelongsToMany
    {
        return $this->cast()->where('key_role', true);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'project_achievement');
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'project_module')->withPivot('sort_order')->orderBy('sort_order');
    }

    public function featuringStories(): MorphToMany
    {
        return $this->morphedByMany(Story::class, 'projectable')->using(Projectable::class);
    }

    public function featuringPages(): MorphToMany
    {
        return $this->morphedByMany(Page::class, 'projectable')->using(Projectable::class);
    }

    public function featuringServices(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'projectable')->using(Projectable::class);
    }

    public function featuringIndustries(): MorphToMany
    {
        return $this->morphedByMany(Industry::class, 'projectable')->using(Projectable::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover_image')->singleFile();
        $this->addMediaCollection('cover_image_mobile')->singleFile();
        $this->addMediaCollection('secondary_image')->singleFile();
        $this->addMediaCollection('video')->singleFile();
        $this->addMediaCollection('video_mobile')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('minified')
            ->width(20);
        $this->addMediaConversion('320')
            ->width(320)
            ->performOnCollections('cover_image', 'cover_image_mobile', 'secondary_image');
        $this->addMediaConversion('thumb')
            ->width(300)
            ->extractVideoFrameAtSecond(1)
            ->performOnCollections('video', 'video_mobile');
    }

    static public function projects($draft = false)
    {
        return self::when(!$draft, fn (Builder $query) => $query->active())
            ->with('industry', 'client', 'services', 'achievements', 'modules', 'media', 'cast')
            ->orderBy('sort_order')
            ->get();
    }

    static public function project($slug, $draft = false)
    {
        return self::where('slug', $slug)
            ->when(!$draft, fn (Builder $query) => $query->active()->with('industry', 'client', 'services', 'achievements', 'cast', 'media', 'activeTestimonials'))
            ->when($draft, fn (Builder $query) => $query->with('industry', 'client', 'services', 'achievements', 'cast', 'media', 'testimonials'))
            ->firstOrFail();
    }
}
