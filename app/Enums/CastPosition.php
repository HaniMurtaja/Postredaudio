<?php

namespace App\Enums;

enum CastPosition: string
{
    use EnumValues;

    case DIRECTOR = 'Director';
    case PRODUCER = 'Producer';
    case ARTDIRECTOR = 'Art Director';
    case EXECPRODUCER = 'Executive Producer';
    case CASTINGDIRECTOR = 'Casting Director';
    case ANIMATIONDEPARTMENT = 'Animation Department';
    case EDITOR = 'Editor';
    case COSTUMEDESIGNER = 'Costume Designer';
    case SETDRESSER = 'Set Dresser';
    case PHOTOGRAPHYDIRECOTR = 'Director of Photography';
    case MAKEUP = 'Make-Up Artist';
    case SOUNDASSISTANT = 'Sound Assistant';
    case CABLEPERSON = 'Cable Person';
    case SOUNDMIXER = 'Sound Mixer';
    case BOOMOPERATOR = 'Boom Operator';
    case FOLEYARTISTS = 'Foley Artist';
}
