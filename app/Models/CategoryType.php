<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\CategoryType as CategoryTypeEnum;

class CategoryType extends Model
{
    use HasFactory;

    protected $casts = [
        'name' => CategoryTypeEnum::class
    ];
    public $timestamps = false;

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
