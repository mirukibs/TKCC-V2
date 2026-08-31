<?php

namespace App\Modules\Sacraments\Application\DTOs;

class SaveSacramentDTO
{
    public function __construct(
        public readonly int $memberId,
        public readonly string $baptismStatus,
        public readonly ?string $baptismDate,
        public readonly ?string $baptismPlace,
        public readonly string $confirmationStatus,
        public readonly ?string $confirmationDate,
        public readonly ?string $confirmationPlace,
        public readonly string $marriageStatus,
        public readonly ?string $marriageDate,
        public readonly ?string $marriagePlace
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            memberId: $data['member_id'],
            baptismStatus: $data['baptism_status'] ?? 'not_baptized',
            baptismDate: $data['baptism_date'] ?? null,
            baptismPlace: $data['baptism_place'] ?? null,
            confirmationStatus: $data['confirmation_status'] ?? 'not_confirmed',
            confirmationDate: $data['confirmation_date'] ?? null,
            confirmationPlace: $data['confirmation_place'] ?? null,
            marriageStatus: $data['marriage_status'] ?? 'single',
            marriageDate: $data['marriage_date'] ?? null,
            marriagePlace: $data['marriage_place'] ?? null
        );
    }
}
