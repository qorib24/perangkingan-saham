<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\RiwayatAnalisis;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestorDashboardController extends Controller
{
    public function index()
    {
        // Ambil riwayat analisis terakhir oleh investor yang login
        $riwayats = RiwayatAnalisis::where('user_id', Auth::id() ?? 1)
                                   ->latest()
                                   ->take(5)
                                   ->get();
        
        $kriterias = Kriteria::all();
        
        return view('investor.dashboard', compact('riwayats', 'kriterias'));
    }
    
    public function showKuesioner()
    {
        $kriterias = Kriteria::all();
        if($kriterias->count() < 2) {
            return redirect()->back()->with('error', 'Kriteria belum cukup untuk melakukan analisis AHP. Minimal dibutuhkan 2 kriteria.');
        }
        
        return view('investor.kuesioner', compact('kriterias'));
    }
}
