<?php

namespace App\Nova\Flexible\Layouts\ContentBlocks;

use App\Nova\Flexible\Layouts\LayoutExtended;
use App\Nova\Flexible\FlexibleExtended;
use App\Nova\Flexible\Layouts\ContentBlocks\Misc\TestimonialLayout\SingleTestimonial;
use Laravel\Nova\Fields\Text;

class TestimonialLayout extends LayoutExtended
{
    protected $name = 'testimonial';
    protected $title = 'Testimonial Layout';

    public function fields()
    {
        return [
            Text::make('Title', 'title')
                ->rules(['max:100']),
            FlexibleExtended::make('Testimonials', 'testimonials')
                ->fullWidth()
                ->addSingleLayout(SingleTestimonial::class)
                ->button('Add Testimonial')
        ];
    }
}
