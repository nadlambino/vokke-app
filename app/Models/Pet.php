<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\PetType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NadLambino\Uploadable\Concerns\Uploadable;

class Pet extends Model
{
    use HasFactory, Uploadable;

    protected $fillable = [
        'type',
        'name',
        'nickname',
        'weight',
        'height',
        'gender',
        'color',
        'friendliness',
        'birthday'
    ];

    protected function casts()
    {
        return [
            'type' => PetType::class,
            'gender' => Gender::class,
            'birthday' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeKangaroos(Builder $query)
    {
        return $query->where('type', PetType::Kangaroo);
    }
}
