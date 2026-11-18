<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\RiwayatAnalisis;
use App\Models\HasilRankingCocoso;
use Illuminate\Http\Request;

class HasilAnalisisController extends Controller
{
    public function show($riwayat_id)
    {
        $riwayat = RiwayatAnalisis::with('detailBobots.kriteria')->findOrFail($riwayat_id);
        
        // Ambil hasil perankingan dan relasikan dengan tabel saham_alternatifs
        $rankings = HasilRankingCocoso::with('saham')
                        ->where('riwayat_id', $riwayat_id)
                        ->orderBy('ranking', 'asc')
                        ->get();

        return view('investor.hasil', compact('riwayat', 'rankings'));
    }
}
