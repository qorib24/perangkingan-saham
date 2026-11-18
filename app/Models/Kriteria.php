<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'atribut',
    ];

    public function nilaiFundamentals()
    {
        return $this->hasMany(NilaiFundamental::class, 'kriteria_id');
    }

    public function detailBobotAhps()
    {
        return $this->hasMany(DetailBobotAhp::class, 'kriteria_id');
    }
}
