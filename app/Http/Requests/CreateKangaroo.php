<?php

namespace App\Http\Requests;

use App\Enums\Friendliness;
use App\Enums\Gender;
use App\Enums\PetType;
use App\Models\Pet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateKangaroo extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', Pet::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['sometimes', 'nullable', 'string', 'max:255'],
            'weight' => ['required', 'decimal:0,2', 'gt:0'],
            'height' => ['required', 'decimal:0,2', 'gt:0'],
            'gender' => ['required', 'in:' . implode(',', Gender::toArray())],
            'color' => ['sometimes', 'nullable', 'string', 'max:255'],
            'friendliness' => ['sometimes', 'nullable', 'in:' . implode(',', Friendliness::toArray())],
            'birthday' => ['required', 'date', 'beforeOrEqual:today'],
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
