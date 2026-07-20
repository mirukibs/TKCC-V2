<?php

namespace App\Modules\Households\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Households\Application\DTOs\RegisterHouseholdDTO;
use App\Modules\Households\Application\DTOs\UpdateHouseholdDTO;
use App\Modules\Households\Application\Services\GetHouseholdService;
use App\Modules\Households\Application\Services\RegisterHouseholdService;
use App\Modules\Households\Application\Services\UpdateHouseholdService;
use App\Modules\Households\Domain\Exceptions\HouseholdNotFoundException;
use App\Modules\Households\Domain\Repositories\HouseholdRepositoryInterface;
use App\Modules\Households\Presentation\Requests\RegisterHouseholdRequest;
use App\Modules\Households\Presentation\Requests\UpdateHouseholdRequest;
use App\Modules\Households\Presentation\Resources\HouseholdResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    private HouseholdRepositoryInterface $repository;

    public function __construct(HouseholdRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'ownership']);
        $households = $this->repository->findAll($filters);

        return response()->json(HouseholdResource::collection($households));
    }

    public function show(int $id, GetHouseholdService $service): JsonResponse
    {
        try {
            $household = $service->execute($id);

            return response()->json(new HouseholdResource($household));
        } catch (HouseholdNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(RegisterHouseholdRequest $request, RegisterHouseholdService $service): JsonResponse
    {
        $dto = new RegisterHouseholdDTO(
            $request->validated('name'),
            $request->validated('community_id'),
            $request->validated('leader_id'),
            $request->validated('ownership')
        );

        $household = $service->execute($dto);

        return response()->json(new HouseholdResource($household), 201);
    }

    public function update(
        int $id,
        UpdateHouseholdRequest $request,
        UpdateHouseholdService $service
    ): JsonResponse {
        try {
            $dto = new UpdateHouseholdDTO(
                $id,
                $request->validated('name'),
                $request->validated('community_id'),
                $request->validated('leader_id'),
                $request->validated('ownership')
            );

            $household = $service->execute($dto);

            return response()->json(new HouseholdResource($household));
        } catch (HouseholdNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repository->delete($id);

        return response()->json(null, 204);
    }
}
