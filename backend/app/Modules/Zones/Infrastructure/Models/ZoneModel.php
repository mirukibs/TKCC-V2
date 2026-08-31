<?php

namespace App\Modules\Zones\Infrastructure\Models;

use App\Modules\Communities\Infrastructure\Models\CommunityModel;
use Illuminate\Database\Eloquent\Model;

class ZoneModel extends Model
{
    protected $table = 'zones';

    protected $fillable = ['name'];

    public function communities()
    {
        return $this->hasMany(CommunityModel::class, 'zone_id');
    }
}
