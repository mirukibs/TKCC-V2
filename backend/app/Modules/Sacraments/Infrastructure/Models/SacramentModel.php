<?php

namespace App\Modules\Sacraments\Infrastructure\Models;

use App\Modules\Members\Infrastructure\Models\MemberModel;
use App\Modules\Sacraments\Infrastructure\Database\Factories\SacramentModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SacramentModel extends Model
{
    use HasFactory;

    protected $table = 'sacraments';

    protected $fillable = [
        'member_id',
        'baptism_status',
        'baptism_date',
        'baptism_place',
        'confirmation_status',
        'confirmation_date',
        'confirmation_place',
        'marriage_status',
        'marriage_date',
        'marriage_place',
    ];

    protected $casts = [
        'baptism_date' => 'date',
        'confirmation_date' => 'date',
        'marriage_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(MemberModel::class, 'member_id');
    }

    protected static function newFactory()
    {
        return SacramentModelFactory::new();
    }
}
