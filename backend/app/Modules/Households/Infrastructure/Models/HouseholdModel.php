<?php

namespace App\Modules\Households\Infrastructure\Models;

use App\Modules\Communities\Infrastructure\Models\CommunityModel;
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

    public function community()
    {
        return $this->belongsTo(CommunityModel::class, 'community_id', 'id');
    }
}
