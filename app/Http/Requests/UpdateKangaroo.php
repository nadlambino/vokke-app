<?php

namespace App\Http\Requests;

use App\Enums\Friendliness;
use App\Enums\Gender;
use App\Enums\PetType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateKangaroo extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('kangaroo'));
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'nickname' => ['sometimes', 'nullable', 'string', 'max:255'],
            'weight' => ['sometimes', 'decimal:0,2', 'gt:0'],
            'height' => ['sometimes', 'decimal:0,2', 'gt:0'],
            'gender' => ['sometimes', 'in:' . implode(',', Gender::toArray())],
            'color' => ['sometimes', 'nullable', 'string', 'max:255'],
            'friendliness' => ['sometimes', 'nullable', 'in:' . implode(',', Friendliness::toArray())],
            'birthday' => ['sometimes', 'date', 'beforeOrEqual:today'],
        ];
    }
}
