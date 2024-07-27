<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateKangaroo;
use App\Http\Requests\UpdateKangaroo;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\Request;
use NadLambino\Uploadable\Actions\Upload;
use Spatie\QueryBuilder\QueryBuilder;

class KangarooController extends Controller
{
    public function index(Request $request)
    {
        $kangaroos = QueryBuilder::for(Pet::query()->kangaroos())
            ->allowedFilters([
                'name',
                'nickname',
                'weight',
                'height',
                'color'
            ])
            ->paginate($request->get('per_page', 10));

        return $this->success(PetResource::collection($kangaroos), [
            'current_page' => $kangaroos->currentPage(),
            'total_pages' => $kangaroos->lastPage(),
            'total_count' => $kangaroos->total(),
        ]);
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
