<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TeamMemberResource;

class DepartmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'display_name' => count(flexibleStringValues($this->display_name)) ? flexibleStringValues($this->display_name) : $this->name,
            'members' => TeamMemberResource::collection($this->teamMembers)
        ];
    }
}
