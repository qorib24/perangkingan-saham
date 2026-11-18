<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\SahamAlternatif;
use App\Models\NilaiFundamental;
use App\Imports\NilaiFundamentalImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NilaiFundamentalController extends Controller
{
    public function index()
    {
        $sahams = SahamAlternatif::with('nilaiFundamentals')->get();
        $kriterias = Kriteria::all();
        $totalNilai = NilaiFundamental::count();
        return view('admin.nilai_fundamental.index', compact('sahams', 'kriterias', 'totalNilai'));
    }

    public function edit(SahamAlternatif $saham)
    {
        $kriterias = Kriteria::all();
        $nilai = $saham->nilaiFundamentals->keyBy('kriteria_id');
        return view('admin.nilai_fundamental.edit', compact('saham', 'kriterias', 'nilai'));
    }

    public function update(Request $request, SahamAlternatif $saham)
    {
        $kriterias = Kriteria::all();
        $inputNilai = $request->input('nilai');

        if ($inputNilai) {
            foreach ($kriterias as $kriteria) {
                if (isset($inputNilai[$kriteria->id])) {
                    NilaiFundamental::updateOrCreate(
                        [
                            'saham_id' => $saham->id,
                            'kriteria_id' => $kriteria->id,
                        ],
                        [
                            'nilai_aktual' => (float)$inputNilai[$kriteria->id],
                        ]
                    );
                }
            }
        }

        return redirect()->route('admin.nilai_fundamental.index')
                         ->with('success', 'Nilai Fundamental saham ' . $saham->kode_saham . ' berhasil diperbarui.');
    }

    /**
     * Import data fundamental dari file Excel (.xlsx / .csv)
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt',
        ]);

        $hapusDataLama = $request->boolean('hapus_data_lama', true);

        try {
            Excel::import(new NilaiFundamentalImport($hapusDataLama), $request->file('file'));

            $pesan = $hapusDataLama 
                ? 'Data lama berhasil dibersihkan dan data baru berhasil di-import dari Excel!' 
                : 'Data nilai fundamental berhasil di-import dan diperbarui!';

            return redirect()->route('admin.nilai_fundamental.index')->with('success', $pesan);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal meng-import file: ' . $e->getMessage());
        }
    }

    /**
     * Hapus semua data nilai fundamental lama (Reset Data)
     */
    public function deleteAll()
    {
        NilaiFundamental::truncate();
        return redirect()->route('admin.nilai_fundamental.index')
                         ->with('success', 'Seluruh data nilai fundamental lama telah berhasil dihapus!');
    }

    /**
     * Download format template CSV/Excel agar memudahkan admin
     */
    public function downloadTemplate()
    {
        $kriterias = Kriteria::all();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_nilai_fundamental_lq45.csv"',
        ];

        return new StreamedResponse(function () use ($kriterias) {
            $handle = fopen('php://output', 'w');
            
            // Header kolom
            $headerRow = ['kode_saham', 'nama_perusahaan', 'sektor'];
            foreach ($kriterias as $k) {
                $headerRow[] = $k->kode;
            }
            fputcsv($handle, $headerRow);

            // Contoh 2 baris data
            $contohRow1 = ['BBCA', 'PT Bank Central Asia Tbk', 'Keuangan', '22.4', '380', '24.2', '4.8', '85.5'];
            $contohRow2 = ['BBRI', 'PT Bank Rakyat Indonesia Tbk', 'Keuangan', '19.8', '410', '13.5', '2.4', '120.3'];
            fputcsv($handle, $contohRow1);
            fputcsv($handle, $contohRow2);

            fclose($handle);
        }, 200, $headers);
    }
}
