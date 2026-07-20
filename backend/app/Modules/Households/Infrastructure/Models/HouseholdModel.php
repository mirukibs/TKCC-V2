<?php

namespace App\Modules\Households\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class HouseholdModel extends Model
{
    protected $table = 'households';

    protected $fillable = [
        'name',
        'community_id',
        'leader_id',
        'ownership',
    ];
}
