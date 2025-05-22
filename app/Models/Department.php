<?php

namespace App\Models;

use App\Models\Pivot\TeamMemberDepartment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Pivot\ContentBlockDepartment;

class Department extends Model
{
    use HasFactory;

    protected $with = ['teamMembers'];

    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class, 'team_member_department')
            ->using(TeamMemberDepartment::class)
            ->orderByPivot('sort_order');
    }

    public function contentBlocks(): BelongsToMany
    {
        return $this->belongsToMany(ContentBlock::class, 'content_block_department')->using(ContentBlockDepartment::class);
    }
}
