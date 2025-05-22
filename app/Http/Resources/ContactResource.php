<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class ContactResource extends JsonResource
{
    use HasFlexible;

    public function toArray($request)
    {
        return [
            'social_links' => flexibleStringValues($this['social_links']),
            'contact_form_field_1_text' => $this['contact_form_field_1_text'],
            'contact_form_field_1_options' => flexibleStringValues($this['contact_form_field_1_options']),
            'contact_form_field_2_text' => $this['contact_form_field_2_text'],
            'contact_form_field_2_options' => flexibleStringValues($this['contact_form_field_2_options']),
            'contact_h1' => $this['contact_h1'],
            'contact_h2' => $this['contact_h2'],
            'contact_email' => $this['contact_email'],
            'contact_show_team_section' => $this['contact_show_team_section'],
            'footer' => $this['footer'],
        ];
    }
}
