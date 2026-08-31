<?php

namespace App\Modules\Sacraments\Domain\Enums;

enum BaptismStatus: string
{
    case NOT_BAPTIZED = 'not_baptized';
    case BAPTIZED = 'baptized';

    public function label(): string
    {
        return match ($this) {
            self::NOT_BAPTIZED => 'Hajabatizwa',
            self::BAPTIZED => 'Amebatizwa',
        };
    }
}
