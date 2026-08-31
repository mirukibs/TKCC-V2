<?php

namespace App\Modules\Sacraments\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Sacraments\Application\DTOs\SaveSacramentDTO;
use App\Modules\Sacraments\Application\Services\GetSacramentService;
use App\Modules\Sacraments\Application\Services\SaveSacramentService;
use App\Modules\Sacraments\Infrastructure\Models\SacramentModel;
use App\Modules\Sacraments\Presentation\Requests\SaveSacramentRequest;
use App\Modules\Sacraments\Presentation\Resources\SacramentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SacramentController extends Controller
{
    public function __construct(
        private SaveSacramentService $saveService,
        private GetSacramentService $getService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 15);
        $filters = $request->only(['search', 'member_id', 'baptism_status', 'confirmation_status', 'marriage_status']);

        // Directly using the infrastructure model for the collection resource to eager load relationships automatically
        $query = SacramentModel::query()->with('member');

        if (isset($filters['search'])) {
            $query->whereHas('member', function ($q) use ($filters) {
                $q->where('first_name', 'like', '%'.$filters['search'].'%')
                    ->orWhere('last_name', 'like', '%'.$filters['search'].'%');
            });
        }

        if (isset($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }
        if (isset($filters['baptism_status'])) {
            $query->where('baptism_status', $filters['baptism_status']);
        }
        if (isset($filters['confirmation_status'])) {
            $query->where('confirmation_status', $filters['confirmation_status']);
        }
        if (isset($filters['marriage_status'])) {
            $query->where('marriage_status', $filters['marriage_status']);
        }

        $sacraments = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => SacramentResource::collection($sacraments->items()),
            'meta' => [
                'current_page' => $sacraments->currentPage(),
                'last_page' => $sacraments->lastPage(),
                'per_page' => $sacraments->perPage(),
                'total' => $sacraments->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $model = SacramentModel::with('member')->findOrFail($id);

            return response()->json([
                'data' => new SacramentResource($model),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sacrament not found'], 404);
        }
    }

    public function showByMember(int $memberId): JsonResponse
    {
        try {
            $model = SacramentModel::with('member')->where('member_id', $memberId)->firstOrFail();

            return response()->json([
                'data' => new SacramentResource($model),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sacrament not found'], 404);
        }
    }

    public function store(SaveSacramentRequest $request): JsonResponse
    {
        $dto = SaveSacramentDTO::fromArray($request->validated());
        $sacrament = $this->saveService->execute($dto);

        $model = SacramentModel::with('member')->find($sacrament->getId());

        return response()->json([
            'message' => 'Sacrament created successfully',
            'data' => new SacramentResource($model),
        ], 201);
    }

    public function update(SaveSacramentRequest $request, int $id): JsonResponse
    {
        // To verify it exists
        $this->getService->getById($id);

        $dto = SaveSacramentDTO::fromArray($request->validated());
        $sacrament = $this->saveService->execute($dto);

        $model = SacramentModel::with('member')->find($sacrament->getId());

        return response()->json([
            'message' => 'Sacrament updated successfully',
            'data' => new SacramentResource($model),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $sacrament = SacramentModel::findOrFail($id);
            $sacrament->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sacrament not found'], 404);
        }
    }
}
