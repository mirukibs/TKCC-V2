<?php

namespace App\Modules\Members\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Members\Application\DTOs\RegisterMemberDTO;
use App\Modules\Members\Application\Services\RegisterMemberService;
use App\Modules\Members\Presentation\Requests\RegisterMemberRequest;
use App\Modules\Members\Presentation\Resources\MemberResource;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;
use Illuminate\Http\JsonResponse;

class MemberController extends Controller
{
    public function __construct(
        private RegisterMemberService $registerMemberService,
        private MemberRepositoryInterface $memberRepository
    ) {}

    public function index(): JsonResponse
    {
        $members = $this->memberRepository->findAll();
        return response()->json(MemberResource::collection($members));
    }

    public function show(int $id): JsonResponse
    {
        $member = $this->memberRepository->findById($id);
        
        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        return response()->json(new MemberResource($member));
    }

    public function store(RegisterMemberRequest $request): JsonResponse
    {
        $dto = new RegisterMemberDTO(
            name: $request->validated('name'),
            dob: $request->validated('dob'),
            gender: $request->validated('gender'),
            phone: $request->validated('phone'),
            householdId: $request->validated('household_id')
        );

        $member = $this->registerMemberService->execute($dto);

        return response()->json(new MemberResource($member), 201);
    }
}
