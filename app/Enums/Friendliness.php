<?php

namespace App\Enums;

enum Friendliness: string
{
    use Arrayable;

    case Friendly = 'friendly';
    case Independent = 'independent';
}
