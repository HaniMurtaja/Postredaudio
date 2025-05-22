<?php

namespace App\Enums;

enum TestimoniableType: string
{
    use EnumValues;

    case Service = 'App\Models\Service';
    case Industry = 'App\Models\Industry';
    case Story = 'App\Models\Story';
    case Project = 'App\Models\Project';
}
