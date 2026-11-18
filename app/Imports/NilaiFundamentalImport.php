<?php

namespace App\Imports;

use App\Models\Kriteria;
use App\Models\SahamAlternatif;
use App\Models\NilaiFundamental;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiFundamentalImport implements ToCollection, WithHeadingRow
{
    protected $hapusDataLama;

    public function __construct(bool $hapusDataLama = true)
    {
        $this->hapusDataLama = $hapusDataLama;
    }

    public function collection(Collection $rows)
    {
        // 1. Hapus data lama jika dicentang oleh user
        if ($this->hapusDataLama) {
            NilaiFundamental::truncate();
        }

        $kriterias = Kriteria::all();

        foreach ($rows as $row) {
            // Ambil kode saham dari kolom 'kode_saham' atau 'kode' atau 'ticker'
            $kodeSaham = strtoupper(trim($row['kode_saham'] ?? $row['kode'] ?? $row['ticker'] ?? ''));

            if (!$kodeSaham) {
                continue;
            }

            // Cari atau buat otomatis jika saham belum terdaftar
            $namaPerusahaan = $row['nama_perusahaan'] ?? $row['nama'] ?? $kodeSaham;
            $sektor = $row['sektor'] ?? 'LQ45';

            $saham = SahamAlternatif::firstOrCreate(
                ['kode_saham' => $kodeSaham],
                [
                    'nama_perusahaan' => $namaPerusahaan,
                    'sektor' => $sektor,
                ]
            );

            // Simpan nilai fundamental per kriteria
            foreach ($kriterias as $kriteria) {
                $keyKode = strtolower($kriteria->kode);
                
                $nilai = null;
                if (isset($row[$keyKode])) {
                    $nilai = $row[$keyKode];
                } elseif (isset($row[$kriteria->kode])) {
                    $nilai = $row[$kriteria->kode];
                }

                if ($nilai !== null && is_numeric($nilai)) {
                    NilaiFundamental::updateOrCreate(
                        [
                            'saham_id' => $saham->id,
                            'kriteria_id' => $kriteria->id,
                        ],
                        [
                            'nilai_aktual' => (float)$nilai,
                        ]
                    );
                }
            }
        }
    }
}
