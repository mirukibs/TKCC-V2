<?php

namespace App\Modules\Members\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class MemberModel extends Model
{
    protected $table = 'members';

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'dob',
        'gender',
        'marital_status',
        'phone',
        'position',
        'employment_status',
        'employment_notes',
        'household_id'
    ];
}
