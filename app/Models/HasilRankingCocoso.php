<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilRankingCocoso extends Model
{
    use HasFactory;

    protected $fillable = [
        'riwayat_id',
        'saham_id',
        'skor_ki',
        'ranking',
    ];

    public function riwayat()
    {
        return $this->belongsTo(RiwayatAnalisis::class, 'riwayat_id');
    }

    public function saham()
    {
        return $this->belongsTo(SahamAlternatif::class, 'saham_id');
    }
}
