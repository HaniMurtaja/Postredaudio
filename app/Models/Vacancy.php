<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class Vacancy extends Model
{
    use HasFactory;

    protected $casts = [
        'responsibilities' => FlexibleCast::class,
        'requirements' => FlexibleCast::class,
        'skills' => FlexibleCast::class
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    static public function vacancies($draft = false)
    {
        return self::when(!$draft, fn (Builder $query) => $query->active())->get();
    }
}
