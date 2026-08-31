<?php

namespace App\Modules\Zones\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Communities\Infrastructure\Models\CommunityModel;

class ZoneModel extends Model
{
    protected $table = 'zones';
    protected $fillable = ['name'];

    public function communities()
    {
        return $this->hasMany(CommunityModel::class, 'zone_id');
    }
}
