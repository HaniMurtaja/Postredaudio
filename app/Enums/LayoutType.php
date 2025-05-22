<?php

namespace App\Enums;

enum LayoutType: string
{
    use EnumValues;

    case HEADER = 'header';
    case GENERAL = 'general';
    case SLIDER = 'slider';
    case DEPARTMENT = 'department';
    case TEXT = 'text';
    case COLUMN = 'column';
    case CLIENTS = 'client';
    case SWITCH = 'switch';
    case TESTIMONIAL = 'testimonial';
}
