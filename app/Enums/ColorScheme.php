<?php

namespace App\Enums;

enum ColorScheme: string
{
    use EnumValues;
    
    case Red = 'red';
    case Black = 'black';
    case Gray = 'gray';
}