<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateKangaroo;
use App\Http\Requests\UpdateKangaroo;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use App\QueryBuilders\Kangaroo\SearchFilter;
use Illuminate\Http\Request;
use NadLambino\Uploadable\Actions\Upload;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class KangarooController extends Controller
{
    public function index(Request $request)
    {
        $kangaroos = QueryBuilder::for(Pet::query()->kangaroos())
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter),
            ])
            ->with('image')
            ->latest()
            ->get();

        return $this->success(PetResource::collection($kangaroos));
    }

    public function store(CreateKangaroo $request)
    {
        Upload::onlyFor(Pet::class);
        $kangaroo = Pet::create($request->validated());

        return $this->success($kangaroo);
    }

    public function update(UpdateKangaroo $request, Pet $kangaroo)
    {
        Upload::onlyFor($kangaroo);
        $kangaroo->update($request->validated());

        if (! $kangaroo->wasChanged()) {
            $kangaroo->updateUploads();
        }

        return $this->success($kangaroo);
    }

    public function destroy(Pet $kangaroo)
    {
        $kangaroo->delete();

        return $this->success([]);
    }
}
