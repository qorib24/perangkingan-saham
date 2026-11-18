<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SahamAlternatif;
use App\Models\RiwayatAnalisis;
use App\Models\HasilRankingCocoso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CocosoController extends Controller
{
    /**
     * Memproses algoritma CoCoSo berdasarkan bobot kriteria AHP.
     */
    public function calculate($riwayat_id)
    {
        // 1. Ambil bobot kriteria dari Fase 1 (AHP)
        $riwayat = RiwayatAnalisis::with('detailBobots.kriteria')->findOrFail($riwayat_id);
        $bobotAHP = $riwayat->detailBobots->keyBy('kriteria_id');
        
        $kriterias = Kriteria::all()->keyBy('id');
        
        // 2. Ambil data nilai aktual (fundamental) saham
        $sahams = SahamAlternatif::with('nilaiFundamentals')->get();
        
        if ($sahams->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada data saham dan nilai fundamental untuk diranking.');
        }

        // Cari Nilai Maksimum (max) dan Minimum (min) tiap Kriteria
        $minMax = [];
        foreach ($kriterias as $k) {
            $minMax[$k->id] = [
                'min' => PHP_FLOAT_MAX,
                'max' => -PHP_FLOAT_MAX,
            ];
        }

        foreach ($sahams as $saham) {
            foreach ($saham->nilaiFundamentals as $nilai) {
                if ($nilai->nilai_aktual < $minMax[$nilai->kriteria_id]['min']) {
                    $minMax[$nilai->kriteria_id]['min'] = $nilai->nilai_aktual;
                }
                if ($nilai->nilai_aktual > $minMax[$nilai->kriteria_id]['max']) {
                    $minMax[$nilai->kriteria_id]['max'] = $nilai->nilai_aktual;
                }
            }
        }

        $hasilSaham = [];
        $sumPiSi = 0;
        $minSi = PHP_FLOAT_MAX;
        $minPi = PHP_FLOAT_MAX;

        foreach ($sahams as $saham) {
            $si = 0; // Total Comparability Sequence
            $pi = 0; // Total Power Weight

            foreach ($saham->nilaiFundamentals as $nilai) {
                $k_id = $nilai->kriteria_id;
                $k = $kriterias[$k_id];
                $aktual = $nilai->nilai_aktual;
                
                // Jika bobot belum diisi di db, fallback ke 0 (mencegah error)
                $bobot = isset($bobotAHP[$k_id]) ? $bobotAHP[$k_id]->bobot_prioritas : 0;

                // 3. Normalisasi CoCoSo
                $min = $minMax[$k_id]['min'];
                $max = $minMax[$k_id]['max'];
                
                // Mencegah division by zero
                $pembagi = ($max - $min) == 0 ? 1 : ($max - $min);

                if ($k->atribut == 'benefit') {
                    $norm = ($aktual - $min) / $pembagi;
                } else { // cost
                    $norm = ($max - $aktual) / $pembagi;
                }

                // Normalisasi pangkat: PHP function pow() error jika basis nol, 
                // Set epsilon kecil apabila base normalisasinya 0 (agar kalkulasi matematika jalan)
                $normForPow = $norm == 0 ? 0.0000001 : $norm; 

                // 4. Hitung Si = Sum(Normalisasi * Bobot AHP)
                $si += ($norm * $bobot);
                // 5. Hitung Pi = Sum(Normalisasi ^ Bobot AHP)
                $pi += pow($normForPow, $bobot);
            }

            // Cari nilai Min(Si) dan Min(Pi) dari seluruh alternatif
            if ($si < $minSi) $minSi = $si;
            if ($pi < $minPi) $minPi = $pi;
            
            $sumPiSi += ($pi + $si);

            $hasilSaham[$saham->id] = [
                'saham_id' => $saham->id,
                'si' => $si,
                'pi' => $pi
            ];
        }

        $lambda = 0.5; // Konstanta strategis standar
        $rankings = [];

        foreach ($hasilSaham as $id => $data) {
            $si = $data['si'];
            $pi = $data['pi'];

            // 6. Hitung 3 Strategi Kompromi (K1, K2, K3)
            $k1 = ($sumPiSi == 0) ? 0 : ($pi + $si) / $sumPiSi;
            
            // Pencegahan division by zero
            $k2 = ($minSi != 0 ? $si / $minSi : 0) + ($minPi != 0 ? $pi / $minPi : 0);
            
            $k3 = ($lambda * $si) + ((1 - $lambda) * $pi);

            // 7. Hitung Nilai Akhir / Skor Kompromi (Ki)
            $ki = pow(($k1 * $k2 * $k3), (1/3)) + ((1/3) * ($k1 + $k2 + $k3));

            $rankings[] = [
                'saham_id' => $id,
                'skor_ki' => $ki
            ];
        }

        // 8. Lakukan arsort() (descending berdasarkan Ki)
        usort($rankings, function($a, $b) {
            return $b['skor_ki'] <=> $a['skor_ki'];
        });

        // 9. Simpan ke tabel hasil_ranking_cocoso
        DB::beginTransaction();
        try {
            // Hapus hasil sebelumnya dari riwayat ini untuk mencegah duplikasi jika dieksekusi ulang
            HasilRankingCocoso::where('riwayat_id', $riwayat_id)->delete();

            $rank = 1;
            foreach ($rankings as $item) {
                HasilRankingCocoso::create([
                    'riwayat_id' => $riwayat_id,
                    'saham_id' => $item['saham_id'],
                    'skor_ki' => $item['skor_ki'],
                    'ranking' => $rank
                ]);
                $rank++;
            }
            DB::commit();

            // Redirect ke halaman hasil analisis lengkap dengan data Ki dan top rekomendasinya
            return redirect()->route('hasil.show', ['riwayat_id' => $riwayat_id])
                             ->with('success', 'Perhitungan AHP dan CoCoSo berhasil diselesaikan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Terjadi kesalahan saat perangkingan CoCoSo: " . $e->getMessage());
        }
    }
}
