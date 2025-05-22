<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamMemberDepartment extends Pivot
{
    protected $table = 'team_member_department';
    public $timestamps = null;
}
