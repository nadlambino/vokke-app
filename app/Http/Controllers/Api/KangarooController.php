<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateKangaroo;
use App\Models\Pet;
use NadLambino\Uploadable\Actions\Upload;

class KangarooController extends Controller
{
    public function store(CreateKangaroo $request)
    {
        Upload::onlyFor(Pet::class);
        $kangaroo = Pet::create($request->validated());

        return $this->success($kangaroo);
    }
}
