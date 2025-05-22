<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;
use App\Nova\Flexible\Layouts\ContentBlocks\HeaderLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\GeneralLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\TextLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\DepartmentLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\SwitchLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\SliderLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\ColumnLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\ClientLayout;
use App\Nova\Flexible\Layouts\ContentBlocks\TestimonialLayout;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;
use App\Models\Pivot\Testimoniable;
use App\Models\Pivot\ContentBlockDepartment;

class ContentBlock extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasFlexible;

    protected $fillable = ['sort_order'];
    protected $with = [
        'media',
        'colorScheme',
        'departments',
        'testimonials',
        'clients'
    ];
    protected $casts = [
        'links' => FlexibleCast::class
    ];

    public function getContentAttribute()
    {
        return $this->flexible('content', [
            'header' => HeaderLayout::class,
            'general' => GeneralLayout::class,
            'text' => TextLayout::class,
            'department' => DepartmentLayout::class,
            'switch' => SwitchLayout::class,
            'slider' => SliderLayout::class,
            'column' => ColumnLayout::class,
            'client' => ClientLayout::class,
            'testimonial' => TestimonialLayout::class,
        ]);
    }

    public function resource(): MorphTo
    {
        return $this->morphTo();
    }

    public function colorScheme(): BelongsTo
    {
        return $this->belongsTo(ColorScheme::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'content_block_department')->using(ContentBlockDepartment::class)->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function testimonials(): MorphToMany
    {
        return $this->morphToMany(Testimonial::class, 'testimoniable')->using(Testimoniable::class)->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function activeTestimonials(): MorphToMany
    {
        return $this->testimonials()->active();
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'content_block_client');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('minified')
            ->width(20);
    }
}
