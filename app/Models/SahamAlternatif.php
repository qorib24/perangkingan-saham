<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SahamAlternatif extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_saham',
        'nama_perusahaan',
        'sektor',
    ];

    public function nilaiFundamentals()
    {
        return $this->hasMany(NilaiFundamental::class, 'saham_id');
    }

    public function hasilRankingCocos()
    {
        return $this->hasMany(HasilRankingCocoso::class, 'saham_id');
    }
}
