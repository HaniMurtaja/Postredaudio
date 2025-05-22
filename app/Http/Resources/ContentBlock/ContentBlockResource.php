<?php

namespace App\Http\Resources\ContentBlock;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\LayoutType;

class ContentBlockResource extends JsonResource
{
    protected $layoutName;

    public function __construct($resource)
    {
        parent::__construct($resource);
        if($this->content->count()){
            $this->layoutName = $this->content[0]->name();
        }
    }

    public function toArray($request)
    {
        return [
            'layout_name' => $this->layoutName,
            'layout_section_title' => $this->section_name,
            'color' => $this->colorScheme->name,
            'scroll_down' => $this->scroll_down,
            'header_key' => $this->header_key,
            'content'  => $this->content->count() ? $this->getContent($this->layoutName, $this->content[0]) : null,
            'sort_order' => $this->sort_order,
            'links' => $this->links->toArray()
        ];
    }

    protected function getContent($layoutName, $content)
    {
        if(in_array($layoutName, LayoutType::values())){
            return new (('App\Http\Resources\ContentBlock\Layouts\\' . ucfirst($layoutName) . 'LayoutResource'))($content);
        }

        return null;
    }
}
