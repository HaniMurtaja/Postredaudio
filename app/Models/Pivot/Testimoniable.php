<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Testimoniable extends MorphPivot
{
    protected $table = 'testimoniable';
    public $timestamps = null;
}
