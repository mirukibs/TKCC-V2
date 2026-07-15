<?php

namespace App\Modules\Members\Domain\Enums;

enum EmploymentStatus: string
{
    case EMPLOYED = 'employed';
    case UNEMPLOYED = 'unemployed';
    case STUDENT = 'student';
    case RETIRED = 'retired';
    case SELF_EMPLOYED = 'self_employed';
}
