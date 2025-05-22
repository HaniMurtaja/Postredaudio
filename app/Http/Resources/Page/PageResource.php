<?php

namespace App\Http\Resources\Page;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'page_type' => array('standard', 'special', 'external')[(int) $this->page_type],
            'sort_order' => $this->sort_order,
            'link' => $this->slug ?? $this->external_link,
            'label' => $this->menu_label ?? $this->name,
            'label_size' => $this->label_size ?? 'small',
            'label_weight' => $this->label_weight ?? 'light',
        ];
    }
}
