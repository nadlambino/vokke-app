<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

abstract class Controller
{
    public function success(array|Collection|EloquentCollection|Model|JsonResource $data, array $metadata = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'metadata' => $metadata,
        ], $status);
    }

    public function error(string $message, array $errors = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
