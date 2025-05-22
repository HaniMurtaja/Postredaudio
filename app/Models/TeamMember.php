<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pivot\TeamMemberDepartment;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class TeamMember extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $with = ['media'];
    protected $casts = [
        'links' => FlexibleCast::class,
    ];

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'team_member_department')->using(TeamMemberDepartment::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('minified')
            ->width(20);
    }
}
