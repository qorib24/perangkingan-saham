<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\SahamAlternatif;
use App\Models\RiwayatAnalisis;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalKriteria = Kriteria::count();
        $totalSaham = SahamAlternatif::count();
        $totalAnalisis = RiwayatAnalisis::count();

        return view('admin.dashboard', compact('totalKriteria', 'totalSaham', 'totalAnalisis'));
    }
}
