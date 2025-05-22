<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks\Misc\TestimonialLayout;

use App\Models\Testimonial;
use Laravel\Nova\Fields\Select;
use App\Nova\Flexible\Layouts\LayoutExtended;
use Spatie\MediaLibrary\HasMedia;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;

class SingleTestimonial extends LayoutExtended implements HasMedia
{
    use HasMediaLibrary;

    protected $name = 'testimonial_layout_single_testimonial';
    protected $title = 'Single Testimonial';
    protected $testimonials;

    public function __construct($title = null, $name = null, $fields = null, $key = null, $attributes = [], callable $removeCallbackMethod = null, $model = null)
    {
        $this->testimonials = Testimonial::get()->pluck('name', 'id')->toArray();
        parent::__construct($title, $name, $fields, $key, $attributes, $removeCallbackMethod, $model);
    }

    public function fields()
    {
        return [
            Select::make('Testimonial')
                ->rules(['required'])
                ->options($this->testimonials),
        ];
    }
}
