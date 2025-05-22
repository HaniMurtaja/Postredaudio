<?php

namespace App\Enums;

enum ModuleType: string
{
    use EnumValues;

    case TITLE = 'Title';
    case COVERIMAGE = 'Cover Image';
    case SERVICE = 'Service';
    case CREDITS = 'Credits';
    case SECONDIMAGE = 'Second Image';
}
