<?php

use App\Http\Controllers\Api\KangarooController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('kangaroos', KangarooController::class);
});
