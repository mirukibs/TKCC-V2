<?php

namespace App\Modules\Members\Infrastructure\Repositories;

use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;
use App\Modules\Members\Infrastructure\Models\MemberModel;
use App\Modules\Members\Domain\ValueObjects\FullName;
use App\Modules\Members\Domain\ValueObjects\DateOfBirth;
use App\Modules\Members\Domain\ValueObjects\PhoneNumber;
use App\Modules\Members\Domain\Enums\Gender;
use App\Modules\Members\Domain\Enums\MaritalStatus;
use App\Modules\Members\Domain\Enums\EmploymentStatus;

class MemberRepository implements MemberRepositoryInterface
{
    public function save(Member $member): Member
    {
        $model = MemberModel::updateOrCreate(
            ['id' => $member->getId()],
            [
                'first_name' => $member->getName()->getFirstName(),
                'last_name' => $member->getName()->getLastName(),
                'middle_name' => $member->getName()->getMiddleName(),
                'dob' => $member->getDob()?->getValue(),
                'gender' => $member->getGender()?->value,
                'marital_status' => $member->getMaritalStatus()?->value,
                'phone' => $member->getPhone()?->getValue(),
                'position' => $member->getPosition(),
                'employment_status' => $member->getEmploymentStatus()?->value,
                'employment_notes' => $member->getEmploymentNotes(),
                'household_id' => $member->getHouseholdId()
            ]
        );

        return $this->toEntity($model);
    }

    public function findById(int $id): ?Member
    {
        $model = MemberModel::find($id);
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findAll(): array
    {
        $models = MemberModel::all();
        $entities = [];
        
        foreach ($models as $model) {
            $entities[] = $this->toEntity($model);
        }
        
        return $entities;
    }

    public function delete(int $id): bool
    {
        return MemberModel::destroy($id) > 0;
    }

    private function toEntity(MemberModel $model): Member
    {
        return new Member(
            id: $model->id,
            name: new FullName($model->first_name, $model->last_name, $model->middle_name),
            dob: $model->dob ? new DateOfBirth($model->dob) : null,
            gender: $model->gender ? Gender::from($model->gender) : null,
            maritalStatus: $model->marital_status ? MaritalStatus::from($model->marital_status) : null,
            phone: $model->phone ? new PhoneNumber($model->phone) : null,
            position: $model->position,
            employmentStatus: $model->employment_status ? EmploymentStatus::from($model->employment_status) : null,
            employmentNotes: $model->employment_notes,
            householdId: $model->household_id
        );
    }
}
