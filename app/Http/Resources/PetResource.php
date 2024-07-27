<?php

namespace App\Http\Resources;

use App\Enums\Friendliness;
use App\Enums\Gender;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'weight' => ((float) $this->weight) . ' kg',
            'height' => ((float) $this->height) . ' cm',
            'gender' => ucfirst($this->gender instanceof Gender ? $this->gender->value : $this->gender),
            'friendliness' => ucfirst($this->friendliness instanceof Friendliness ? $this->friendliness->value : $this->friendliness),
            'birthday' => $this->birthday->format('Y-m-d'),
        ]);
    }
}
