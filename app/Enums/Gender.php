<?php

namespace App\Enums;

enum Gender: string
{
    use Arrayable;

    case Male = 'male';
    case Female = 'female';
}
