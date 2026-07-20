<?php

namespace App\Modules\Households\Domain\Enums;

enum OwnershipType: string
{
    case OWNED = 'owned';
    case RENTED = 'rented';
}
