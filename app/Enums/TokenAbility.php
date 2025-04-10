<?php

namespace App\Enums;

enum TokenAbility: string
{
    case ACCESS_API = 'access';
    case REFRESH_TOKEN = 'refresh';
}
