<?php

namespace App\Modules\Communities\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityModel extends Model
{
    protected $table = 'communities';
    protected $fillable = ['name', 'zone_id'];
}
