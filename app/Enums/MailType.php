<?php

namespace App\Enums;

enum MailType: string
{
    use EnumValues;

    case Contact = 'contact';
    case Vacancy = 'vacancy';
}
