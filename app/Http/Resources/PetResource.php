<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use NadLambino\Uploadable\Facades\Storage;

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
            'birthday' => $this->birthday->format('Y-m-d'),
            'image_url' => $this->whenLoaded('image', function () {
                return $this->image ?
                    Storage::disk($this->image->disk)->url($this->image->path) :
                    null;
            }),
        ]);
    }
}
