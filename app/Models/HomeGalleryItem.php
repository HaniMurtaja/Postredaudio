<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Builder;

class HomeGalleryItem extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    public $timestamps = false;
    protected $fillable = [
        'title',
        'sort_order',
        'resource_id',
        'resource_type',
    ];
    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => false,
    ];

    public function galleryResource(): MorphTo
    {
        return $this->morphTo('galleryResource', 'resource_type', 'resource_id', 'id');
    }

    static public function gallery($draft = false)
    {
        return self::when(!$draft, fn (Builder $query) => $query->whereHas('galleryResource', function($query) {
                $query->where('active', 1);
            })->with('galleryResource', 'galleryResource.media', 'galleryResource.services'))
            ->when($draft, fn (Builder $query) => $query->with('galleryResource', 'galleryResource.media', 'galleryResource.services'))
            ->get()
            ->sortBy('sort_order');
    }
}
