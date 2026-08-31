<?php

namespace App\Modules\Members\Infrastructure\Models;

use App\Modules\Households\Infrastructure\Models\HouseholdModel;
use App\Modules\Sacraments\Infrastructure\Models\SacramentModel;
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
        'household_id',
    ];

    public function sacrament()
    {
        return $this->hasOne(SacramentModel::class, 'member_id', 'id');
    }

    public function household()
    {
        return $this->belongsTo(HouseholdModel::class, 'household_id', 'id');
    }
}
