<?php

namespace App\Enums;

enum TextSize: string
{
    use EnumValues;
    
    case Small = 'small';
    case Medium = 'medium';
    case Large = 'large';
}