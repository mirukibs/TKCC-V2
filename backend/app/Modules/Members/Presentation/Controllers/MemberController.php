<?php

namespace App\Modules\Members\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Members\Application\DTOs\RegisterMemberDTO;
use App\Modules\Members\Application\DTOs\UpdateMemberDTO;
use App\Modules\Members\Application\Services\GetMemberService;
use App\Modules\Members\Application\Services\RegisterMemberService;
use App\Modules\Members\Application\Services\UpdateMemberService;
use App\Modules\Members\Domain\Exceptions\MemberNotFoundException;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;
use App\Modules\Members\Presentation\Requests\RegisterMemberRequest;
use App\Modules\Members\Presentation\Requests\UpdateMemberRequest;
use App\Modules\Members\Presentation\Resources\MemberResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(
        private RegisterMemberService $registerMemberService,
        private UpdateMemberService $updateMemberService,
        private GetMemberService $getMemberService,
        private MemberRepositoryInterface $memberRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'status']);
        $members = $this->memberRepository->findAll($filters);

        return response()->json(MemberResource::collection($members));
    }

    public function show(int $id): JsonResponse
    {
        $member = $this->getMemberService->execute($id);

        if (! $member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        return response()->json(new MemberResource($member));
    }

    public function store(RegisterMemberRequest $request): JsonResponse
    {
        $dto = new RegisterMemberDTO(
            firstName: $request->validated('first_name'),
            lastName: $request->validated('last_name'),
            middleName: $request->validated('middle_name'),
            dob: $request->validated('dob'),
            gender: $request->validated('gender'),
            maritalStatus: $request->validated('marital_status'),
            phone: $request->validated('phone'),
            position: $request->validated('position'),
            employmentStatus: $request->validated('employment_status'),
            employmentNotes: $request->validated('employment_notes'),
            householdId: $request->validated('household_id')
        );

        $member = $this->registerMemberService->execute($dto);

        return response()->json(new MemberResource($member), 201);
    }

    public function update(int $id, UpdateMemberRequest $request): JsonResponse
    {
        try {
            $dto = new UpdateMemberDTO(
                id: $id,
                firstName: $request->validated('first_name'),
                lastName: $request->validated('last_name'),
                middleName: $request->validated('middle_name'),
                dob: $request->validated('dob'),
                gender: $request->validated('gender'),
                maritalStatus: $request->validated('marital_status'),
                phone: $request->validated('phone'),
                position: $request->validated('position'),
                employmentStatus: $request->validated('employment_status'),
                employmentNotes: $request->validated('employment_notes'),
                householdId: $request->validated('household_id')
            );

            $member = $this->updateMemberService->execute($dto);

            return response()->json(new MemberResource($member));
        } catch (MemberNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->memberRepository->delete($id);

        return response()->json(null, 204);
    }
}
