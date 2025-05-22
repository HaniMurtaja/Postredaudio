<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Achievement extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public $timestamps = false;
    protected $with = ['media'];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_achievement');
    }

    public function testimonials(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'testimonial_achievement');
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }
}
