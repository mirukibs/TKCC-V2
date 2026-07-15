<?php

namespace App\Modules\Members\Application\Services;

use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;

class GetMemberService
{
    public function __construct(
        private MemberRepositoryInterface $memberRepository
    ) {}

    public function execute(int $id): ?Member
    {
        return $this->memberRepository->findById($id);
    }
}
