<?php

namespace App\Modules\Sacraments\Domain\Entities;

use App\Modules\Sacraments\Domain\Enums\BaptismStatus;
use App\Modules\Sacraments\Domain\Enums\ConfirmationStatus;
use App\Modules\Sacraments\Domain\Enums\MarriageStatus;
use DateTimeImmutable;

class Sacrament
{
    public function __construct(
        private ?int $id,
        private int $memberId,
        private BaptismStatus $baptismStatus,
        private ?DateTimeImmutable $baptismDate,
        private ?string $baptismPlace,
        private ConfirmationStatus $confirmationStatus,
        private ?DateTimeImmutable $confirmationDate,
        private ?string $confirmationPlace,
        private MarriageStatus $marriageStatus,
        private ?DateTimeImmutable $marriageDate,
        private ?string $marriagePlace,
        private ?DateTimeImmutable $createdAt = null,
        private ?DateTimeImmutable $updatedAt = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMemberId(): int
    {
        return $this->memberId;
    }

    public function getBaptismStatus(): BaptismStatus
    {
        return $this->baptismStatus;
    }

    public function getBaptismDate(): ?DateTimeImmutable
    {
        return $this->baptismDate;
    }

    public function getBaptismPlace(): ?string
    {
        return $this->baptismPlace;
    }

    public function getConfirmationStatus(): ConfirmationStatus
    {
        return $this->confirmationStatus;
    }

    public function getConfirmationDate(): ?DateTimeImmutable
    {
        return $this->confirmationDate;
    }

    public function getConfirmationPlace(): ?string
    {
        return $this->confirmationPlace;
    }

    public function getMarriageStatus(): MarriageStatus
    {
        return $this->marriageStatus;
    }

    public function getMarriageDate(): ?DateTimeImmutable
    {
        return $this->marriageDate;
    }

    public function getMarriagePlace(): ?string
    {
        return $this->marriagePlace;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'member_id' => $this->memberId,
            'baptism_status' => $this->baptismStatus->value,
            'baptism_date' => $this->baptismDate?->format('Y-m-d'),
            'baptism_place' => $this->baptismPlace,
            'confirmation_status' => $this->confirmationStatus->value,
            'confirmation_date' => $this->confirmationDate?->format('Y-m-d'),
            'confirmation_place' => $this->confirmationPlace,
            'marriage_status' => $this->marriageStatus->value,
            'marriage_date' => $this->marriageDate?->format('Y-m-d'),
            'marriage_place' => $this->marriagePlace,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
