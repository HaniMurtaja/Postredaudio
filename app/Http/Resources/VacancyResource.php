<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'about' => $this->about,
            'external_link' => $this->external_link,
            'description' => $this->description,
            'responsibilities' => array_map(function ($responsibility) {
                return $responsibility['description'];
            }, $this->responsibilities->toArray()),
            'requirements' => array_map(function ($requirement) {
                return $requirement['description'];
            }, $this->requirements->toArray()),
            'skills' => array_map(function ($skill) {
                return $skill['description'];
            }, $this->skills->toArray()),
        ];
    }
}
