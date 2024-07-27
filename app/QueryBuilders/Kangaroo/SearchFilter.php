<?php

namespace App\QueryBuilders\Kangaroo;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where(function (Builder $query) use ($value) {
            $query->where('name', 'like', "%{$value}%")
                ->orWhere('nickname', 'like', "%{$value}%")
                ->orWhere('weight', 'like', "%{$value}%")
                ->orWhere('height', 'like', "%{$value}%")
                ->orWhere('color', 'like', "%{$value}%");
        });
    }
}
