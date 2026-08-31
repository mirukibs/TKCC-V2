<?php

namespace App\Modules\Communities\Infrastructure\Models;

use App\Modules\Zones\Infrastructure\Models\ZoneModel;
use Illuminate\Database\Eloquent\Model;

class CommunityModel extends Model
{
    protected $table = 'communities';

    protected $fillable = ['name', 'zone_id'];

    public function zone()
    {
        return $this->belongsTo(ZoneModel::class, 'zone_id', 'id');
    }
}
