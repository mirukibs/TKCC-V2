<?php

namespace App\Modules\Members\Infrastructure\Repositories;

use App\Modules\Members\Domain\Entities\Member;
use App\Modules\Members\Domain\Enums\EmploymentStatus;
use App\Modules\Members\Domain\Enums\Gender;
use App\Modules\Members\Domain\Enums\MaritalStatus;
use App\Modules\Members\Domain\Repositories\MemberRepositoryInterface;
use App\Modules\Members\Domain\ValueObjects\DateOfBirth;
use App\Modules\Members\Domain\ValueObjects\FullName;
use App\Modules\Members\Domain\ValueObjects\PhoneNumber;
use App\Modules\Members\Infrastructure\Models\MemberModel;

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
                'household_id' => $member->getHouseholdId(),
            ]
        );

        return $this->toEntity($model);
    }

    public function findById(int $id): ?Member
    {
        $model = MemberModel::with(['sacrament', 'household.community.zone'])->find($id);

        if (! $model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findAll(array $filters = []): array
    {
        $query = MemberModel::query();

        if (! empty($filters['search'])) {
            $search = '%'.$filters['search'].'%';
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', $search)
                    ->orWhere('last_name', 'LIKE', $search)
                    ->orWhere('middle_name', 'LIKE', $search);
            });
        }

        if (! empty($filters['status'])) {
            $query->where('employment_status', $filters['status']);
        }

        $models = $query->get();
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
        $entity = new Member(
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

        $aggregates = [];

        if ($model->relationLoaded('sacrament') && $model->sacrament) {
            $aggregates['sacrament'] = [
                'baptism_status' => $model->sacrament->baptism_status,
                'baptism_date' => $model->sacrament->baptism_date?->format('Y-m-d'),
                'baptism_place' => $model->sacrament->baptism_place,
                'confirmation_status' => $model->sacrament->confirmation_status,
                'confirmation_date' => $model->sacrament->confirmation_date?->format('Y-m-d'),
                'confirmation_place' => $model->sacrament->confirmation_place,
                'marriage_status' => $model->sacrament->marriage_status,
                'marriage_date' => $model->sacrament->marriage_date?->format('Y-m-d'),
                'marriage_place' => $model->sacrament->marriage_place,
            ];
        }

        if ($model->relationLoaded('household') && $model->household) {
            $aggregates['household'] = [
                'id' => $model->household->id,
                'name' => $model->household->name,
                'leader_id' => $model->household->leader_id,
            ];

            if ($model->household->relationLoaded('community') && $model->household->community) {
                $aggregates['community'] = [
                    'id' => $model->household->community->id,
                    'name' => $model->household->community->name,
                ];

                if ($model->household->community->relationLoaded('zone') && $model->household->community->zone) {
                    $aggregates['zone'] = [
                        'id' => $model->household->community->zone->id,
                        'name' => $model->household->community->zone->name,
                    ];
                }
            }
        }

        $entity->setAggregates($aggregates);

        return $entity;
    }
}
