<?php

namespace App\Enums;

enum ContentBlockResource: string
{
    use EnumValues;

    case Service = 'App\Models\Service';
    case Industry = 'App\Models\Industry';
    case Page = 'App\Models\Page';
}
