<?php

namespace App\Modules\Sacraments\Domain\Enums;

enum MarriageStatus: string
{
    case SINGLE = 'single';
    case MARRIED = 'married';

    public function label(): string
    {
        return match ($this) {
            self::SINGLE => 'Hajaoa/Hajaolewa',
            self::MARRIED => 'Ameoa/Ameolewa',
        };
    }
}
