<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Category extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    protected $with = [
        'type'
    ];
    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function newModelQuery()
    {
        return parent::newModelQuery()->orderBy('sort_order');
    }

    protected $table = 'categories';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->forceFill(['category_type_id' => CategoryType::where('name', static::class)->firstOrFail()->id]);
        });
    }

    public static function booted()
    {
        static::addGlobalScope(static::class, function ($builder) {
            $builder->whereHas('type', function ($query) {
                $query->where('name', static::class);
            });
        });
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CategoryType::class, 'category_type_id');
    }
}
