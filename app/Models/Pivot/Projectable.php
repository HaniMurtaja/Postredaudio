<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Projectable extends MorphPivot
{
    protected $table = 'projectables';
    public $timestamps = null;
}
