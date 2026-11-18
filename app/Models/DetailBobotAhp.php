<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBobotAhp extends Model
{
    use HasFactory;

    protected $fillable = [
        'riwayat_id',
        'kriteria_id',
        'bobot_prioritas',
    ];

    public function riwayat()
    {
        return $this->belongsTo(RiwayatAnalisis::class, 'riwayat_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
