<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateKangaroo;
use App\Http\Requests\UpdateKangaroo;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use App\Models\User;
use App\QueryBuilders\Kangaroo\SearchFilter;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use NadLambino\Uploadable\Actions\Upload;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class KangarooController extends Controller
{
    private User|Authenticatable $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function index(): JsonResponse
    {
        $kangaroos = QueryBuilder::for($this->user->pet()->kangaroos())
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter),
            ])
            ->with('image')
            ->latest()
            ->get();

        return $this->success(PetResource::collection($kangaroos));
    }

    public function store(CreateKangaroo $request): JsonResponse
    {
        Upload::onlyFor(Pet::class);
        $kangaroo = $this->user->pet()->create($request->validated());

        return $this->success($kangaroo);
    }

    public function update(UpdateKangaroo $request, Pet $kangaroo): JsonResponse
    {
        Upload::onlyFor($kangaroo);
        $kangaroo->update($request->validated());

        if (! $kangaroo->wasChanged()) {
            $kangaroo->updateUploads();
        }

        return $this->success($kangaroo);
    }

    public function destroy(Pet $kangaroo): JsonResponse
    {
        Gate::authorize('delete', $kangaroo);

        $kangaroo->delete();

        return $this->success([]);
    }
}
