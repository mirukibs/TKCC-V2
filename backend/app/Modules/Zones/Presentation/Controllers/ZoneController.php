<?php

namespace App\Modules\Zones\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Zones\Application\DTOs\RegisterZoneDTO;
use App\Modules\Zones\Application\DTOs\UpdateZoneDTO;
use App\Modules\Zones\Application\Services\DeleteZoneService;
use App\Modules\Zones\Application\Services\GetZoneService;
use App\Modules\Zones\Application\Services\ListZonesService;
use App\Modules\Zones\Application\Services\RegisterZoneService;
use App\Modules\Zones\Application\Services\UpdateZoneService;
use App\Modules\Zones\Domain\Exceptions\ZoneNotFoundException;
use App\Modules\Zones\Presentation\Requests\RegisterZoneRequest;
use App\Modules\Zones\Presentation\Requests\UpdateZoneRequest;
use App\Modules\Zones\Presentation\Resources\ZoneResource;
use Illuminate\Http\JsonResponse;

class ZoneController extends Controller
{
    public function __construct(
        private RegisterZoneService $registerZoneService,
        private UpdateZoneService $updateZoneService,
        private GetZoneService $getZoneService,
        private ListZonesService $listZonesService,
        private DeleteZoneService $deleteZoneService
    ) {}

    public function index(): JsonResponse
    {
        $zones = $this->listZonesService->execute();

        return response()->json(ZoneResource::collection($zones));
    }

    public function store(RegisterZoneRequest $request): JsonResponse
    {
        $dto = new RegisterZoneDTO($request->validated('name'));
        $zone = $this->registerZoneService->execute($dto);

        return response()->json(new ZoneResource($zone), 201);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $zone = $this->getZoneService->execute($id);

            return response()->json(new ZoneResource($zone));
        } catch (ZoneNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(UpdateZoneRequest $request, int $id): JsonResponse
    {
        try {
            $dto = new UpdateZoneDTO($id, $request->validated('name'));
            $zone = $this->updateZoneService->execute($dto);

            return response()->json(new ZoneResource($zone));
        } catch (ZoneNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteZoneService->execute($id);

            return response()->json(null, 204);
        } catch (ZoneNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
