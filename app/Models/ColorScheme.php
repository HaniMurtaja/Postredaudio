<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ColorScheme extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function contentBlocks(): HasMany
    {
        return $this->hasMany(ContentBlock::class);
    }
}
