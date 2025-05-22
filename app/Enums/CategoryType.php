<?php

namespace App\Enums;

enum CategoryType: string
{
    use EnumValues;
    
    case Service = 'App\Models\Service';
    case Industry = 'App\Models\Industry';
}