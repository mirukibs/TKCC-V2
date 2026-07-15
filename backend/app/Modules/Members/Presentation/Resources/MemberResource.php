<?php

namespace App\Modules\Members\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Members\Domain\Entities\Member;

class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Member $member */
        $member = $this->resource;

        return [
            'id' => $member->getId(),
            'first_name' => $member->getName()->getFirstName(),
            'last_name' => $member->getName()->getLastName(),
            'middle_name' => $member->getName()->getMiddleName(),
            'full_name' => $member->getName()->getValue(),
            'dob' => $member->getDob()?->getValue(),
            'gender' => $member->getGender()?->value,
            'marital_status' => $member->getMaritalStatus()?->value,
            'phone' => $member->getPhone()?->getValue(),
            'position' => $member->getPosition(),
            'employment_status' => $member->getEmploymentStatus()?->value,
            'employment_notes' => $member->getEmploymentNotes(),
            'household_id' => $member->getHouseholdId()
        ];
    }
}
