<?php

namespace App\Http\Resources\ContentBlock\Layouts;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DepartmentResource;

class DepartmentLayoutResource extends JsonResource
{
    public function toArray($request)
    {
        if($this->resource->getModel()) {
            $departments = $this->resource->getModel()->departments;
        }else{
            $departments = collect();
        }

        return [
            'type' => $departments->count() > 0 ? ($departments->count() > 1 ? 'multiple' : ($departments->first()->name === 'Executive Members' ? 'executive' : 'single')) : null,
            'style' => $this->resource->getAttribute('style') ?? 'standard',
            'caption' => $this->resource->getAttribute('caption'),
            'departments' => DepartmentResource::collection($departments)
        ];
    }
}
