<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\RiwayatAnalisis;
use App\Models\DetailBobotAhp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AhpController extends Controller
{
    /**
     * Memproses perhitungan bobot kriteria AHP dari matriks preferensi.
     */
    public function calculate(Request $request)
    {
        // 1. Tangkap input matriks nxn dari form user
        // Struktur input: $request->matrix[$baris_id][$kolom_id] = nilai_skala
        $matrix = $request->input('matrix');
        if (!$matrix || count($matrix) < 2) {
            return redirect()->back()->with('error', 'Data matriks tidak lengkap.');
        }

        $n = count($matrix); // Jumlah kriteria

        // RI (Random Index) array untuk AHP berdasarkan jumlah kriteria 'n'
        $ri_values = [1 => 0.00, 2 => 0.00, 3 => 0.58, 4 => 0.90, 5 => 1.12, 6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49];
        $ri = $ri_values[$n] ?? 1.12; 

        // 2. Hitung jumlah tiap kolom
        $colSums = [];
        $kriteriaIds = array_keys($matrix);
        foreach ($kriteriaIds as $j) {
            $sum = 0;
            foreach ($kriteriaIds as $i) {
                // Konversi string fraction "1/3" ke float opsional di sini jika dari frontend masih string
                $val = is_numeric($matrix[$i][$j]) ? (float)$matrix[$i][$j] : eval("return {$matrix[$i][$j]};");
                $matrix[$i][$j] = $val;
                $sum += $val;
            }
            $colSums[$j] = $sum;
        }

        // 3. Buat Matriks Nilai Kriteria (Normalisasi) & 4. Hitung Eigen Vector (Bobot per kriteria)
        $normalizedMatrix = [];
        $eigenVectors = [];
        foreach ($kriteriaIds as $i) {
            $rowSum = 0;
            foreach ($kriteriaIds as $j) {
                $normValue = $matrix[$i][$j] / $colSums[$j];
                $normalizedMatrix[$i][$j] = $normValue;
                $rowSum += $normValue;
            }
            $eigenVectors[$i] = $rowSum / $n;
        }

        // 5. Hitung Eigen Value Maksimum (Lambda Max)
        $lambdaMax = 0;
        foreach ($kriteriaIds as $i) {
            $lambdaMax += $colSums[$i] * $eigenVectors[$i];
        }

        // 6. Hitung Consistency Index (CI)
        $ci = ($lambdaMax - $n) / ($n - 1);

        // 7. Hitung Consistency Ratio (CR)
        // Hindari division by zero jika matriks ukuran 1x1 atau 2x2 yang mana RI-nya 0
        $cr = ($ri == 0) ? 0 : ($ci / $ri);

        // 8. Simpan bobot ke tabel detail_bobot_ahp
        DB::beginTransaction();
        try {
            $riwayat = RiwayatAnalisis::create([
                'user_id' => Auth::id() ?? 1, // Jika belum ada Auth penuh, set ke ID 1
                'tanggal' => now()->toDateString(),
                'cr_value' => $cr,
            ]);

            foreach ($eigenVectors as $kriteriaId => $bobot) {
                DetailBobotAhp::create([
                    'riwayat_id' => $riwayat->id,
                    'kriteria_id' => $kriteriaId,
                    'bobot_prioritas' => $bobot,
                ]);
            }
            
            DB::commit();

            // Jika matriks tidak konsisten
            if ($cr > 0.1) {
                return redirect()->back()->with('error', "Consistency Ratio (CR) = " . round($cr, 3) . ". Matriks tidak konsisten (> 0.1), harap isi kuesioner kembali dengan preferensi yang lebih logis.");
            }

            // Jika konsisten (CR <= 0.1), redirect ke algoritma perangkingan CoCoSo
            return redirect()->route('cocoso.calculate', ['riwayat_id' => $riwayat->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Terjadi kesalahan saat memproses AHP: " . $e->getMessage());
        }
    }
}
