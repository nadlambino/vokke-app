<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\PetType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'nickname',
        'weight',
        'height',
        'gender'
    ];

    protected function casts()
    {
        return [
            'type' => PetType::class,
            'gender' => Gender::class
        ];
    }
}
