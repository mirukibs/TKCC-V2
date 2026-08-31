<?php

namespace App\Modules\Sacraments\Domain\Enums;

enum ConfirmationStatus: string
{
    case NOT_CONFIRMED = 'not_confirmed';
    case CONFIRMED = 'confirmed';

    public function label(): string
    {
        return match ($this) {
            self::NOT_CONFIRMED => 'Hajapewa Kipaimara',
            self::CONFIRMED => 'Amepewa Kipaimara',
        };
    }
}
