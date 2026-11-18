<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiFundamental extends Model
{
    use HasFactory;

    protected $fillable = [
        'saham_id',
        'kriteria_id',
        'nilai_aktual',
    ];

    public function saham()
    {
        return $this->belongsTo(SahamAlternatif::class, 'saham_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
