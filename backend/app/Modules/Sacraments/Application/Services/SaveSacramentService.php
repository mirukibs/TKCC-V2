<?php

namespace App\Modules\Sacraments\Application\Services;

use App\Modules\Sacraments\Application\DTOs\SaveSacramentDTO;
use App\Modules\Sacraments\Domain\Entities\Sacrament;
use App\Modules\Sacraments\Domain\Enums\BaptismStatus;
use App\Modules\Sacraments\Domain\Enums\ConfirmationStatus;
use App\Modules\Sacraments\Domain\Enums\MarriageStatus;
use App\Modules\Sacraments\Domain\Repositories\SacramentRepositoryInterface;
use DateTimeImmutable;

class SaveSacramentService
{
    public function __construct(
        private SacramentRepositoryInterface $repository
    ) {}

    public function execute(SaveSacramentDTO $dto): Sacrament
    {
        // Try to find existing sacrament for this member
        $existing = $this->repository->findByMemberId($dto->memberId);

        $sacrament = new Sacrament(
            $existing ? $existing->getId() : null,
            $dto->memberId,
            BaptismStatus::from($dto->baptismStatus),
            $dto->baptismDate ? new DateTimeImmutable($dto->baptismDate) : null,
            $dto->baptismPlace,
            ConfirmationStatus::from($dto->confirmationStatus),
            $dto->confirmationDate ? new DateTimeImmutable($dto->confirmationDate) : null,
            $dto->confirmationPlace,
            MarriageStatus::from($dto->marriageStatus),
            $dto->marriageDate ? new DateTimeImmutable($dto->marriageDate) : null,
            $dto->marriagePlace
        );

        return $this->repository->save($sacrament);
    }
}
