<?php

namespace App\Http\Requests;

use App\Enums\Friendliness;
use App\Enums\Gender;
use App\Enums\PetType;
use Illuminate\Foundation\Http\FormRequest;

class CreateKangaroo extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['sometimes', 'nullable', 'string', 'max:255'],
            'weight' => ['required', 'decimal:2', 'gt:0'],
            'height' => ['required', 'decimal:2', 'gt:0'],
            'gender' => ['required', 'in:' . implode(',', Gender::cases())],
            'color' => ['sometimes', 'nullable', 'string', 'max:255'],
            'friendliness' => ['sometimes', 'nullable', 'in:' . implode(',', Friendliness::cases())],
            'birthday' => ['required', 'date', 'before:tomorrow'],
        ];
    }

    public function validated($key = null, $default = null): mixed
    {
        $validated = parent::validated();

        if ($key === null) {
            return array_merge($validated, [
                'type' => PetType::Kangaroo
            ]);
        }

        return $validated[$key] ?? $default;
    }
}
