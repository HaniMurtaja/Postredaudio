<?php

namespace App\Enums;

enum CountableResource: string
{
    use EnumValues;

    case Story = 'App\Models\Story';
    case Service = 'App\Models\Service';
    case Industry = 'App\Models\Industry';
    case Project = 'App\Models\Project';
    case Vacancy = 'App\Models\Vacancy';
}
