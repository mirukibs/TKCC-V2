<?php

namespace App\Modules\Communities\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Communities\Application\DTOs\RegisterCommunityDTO;
use App\Modules\Communities\Application\DTOs\UpdateCommunityDTO;
use App\Modules\Communities\Application\Services\DeleteCommunityService;
use App\Modules\Communities\Application\Services\GetCommunityService;
use App\Modules\Communities\Application\Services\ListCommunitiesService;
use App\Modules\Communities\Application\Services\RegisterCommunityService;
use App\Modules\Communities\Application\Services\UpdateCommunityService;
use App\Modules\Communities\Presentation\Requests\RegisterCommunityRequest;
use App\Modules\Communities\Presentation\Requests\UpdateCommunityRequest;
use App\Modules\Communities\Presentation\Resources\CommunityResource;
use Exception;

class CommunityController extends Controller
{
    public function index(ListCommunitiesService $service)
    {
        $communities = $service->execute();

        return CommunityResource::collection($communities);
    }

    public function store(RegisterCommunityRequest $request, RegisterCommunityService $service)
    {
        $dto = new RegisterCommunityDTO(
            $request->validated('name'),
            $request->validated('zone_id')
        );

        $community = $service->execute($dto);

        return response()->json(new CommunityResource($community), 201);
    }

    public function show(int $id, GetCommunityService $service)
    {
        try {
            $community = $service->execute($id);

            return new CommunityResource($community);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(int $id, UpdateCommunityRequest $request, UpdateCommunityService $service)
    {
        try {
            $dto = new UpdateCommunityDTO(
                $request->validated('name'),
                $request->validated('zone_id')
            );
            $community = $service->execute($id, $dto);

            return new CommunityResource($community);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id, DeleteCommunityService $service)
    {
        try {
            $service->execute($id);

            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
