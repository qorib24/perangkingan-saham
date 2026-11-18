<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatAnalisis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'cr_value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailBobots()
    {
        return $this->hasMany(DetailBobotAhp::class, 'riwayat_id');
    }

    public function hasilRankings()
    {
        return $this->hasMany(HasilRankingCocoso::class, 'riwayat_id');
    }
}
