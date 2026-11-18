<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kriteria;
use App\Models\SahamAlternatif;
use App\Models\NilaiFundamental;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Default (Admin & Investor)
        $admin = User::firstOrCreate(
            ['email' => 'admin@lq45.com'],
            [
                'name' => 'Administrator SPK',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $investor = User::firstOrCreate(
            ['email' => 'investor@lq45.com'],
            [
                'name' => 'Investor LQ45',
                'password' => Hash::make('password123'),
                'role' => 'investor',
            ]
        );

        // 2. Data Master Kriteria
        $kriterias = [
            [
                'kode' => 'C1',
                'nama' => 'Return on Equity (ROE)',
                'atribut' => 'benefit',
            ],
            [
                'kode' => 'C2',
                'nama' => 'Earning Per Share (EPS)',
                'atribut' => 'benefit',
            ],
            [
                'kode' => 'C3',
                'nama' => 'Price to Earnings Ratio (PER)',
                'atribut' => 'cost',
            ],
            [
                'kode' => 'C4',
                'nama' => 'Price to Book Value (PBV)',
                'atribut' => 'cost',
            ],
            [
                'kode' => 'C5',
                'nama' => 'Volume Transaksi Rata-rata',
                'atribut' => 'benefit',
            ],
        ];

        $kriteriaModels = [];
        foreach ($kriterias as $k) {
            $kriteriaModels[$k['kode']] = Kriteria::updateOrCreate(['kode' => $k['kode']], $k);
        }

        // 3. Data Master Saham Alternatif (Sampel Unggulan LQ45)
        $sahams = [
            [
                'kode_saham' => 'BBCA',
                'nama_perusahaan' => 'PT Bank Central Asia Tbk',
                'sektor' => 'Keuangan',
                'nilai' => ['C1' => 22.4, 'C2' => 380, 'C3' => 24.2, 'C4' => 4.8, 'C5' => 85.5],
            ],
            [
                'kode_saham' => 'BBRI',
                'nama_perusahaan' => 'PT Bank Rakyat Indonesia (Persero) Tbk',
                'sektor' => 'Keuangan',
                'nilai' => ['C1' => 19.8, 'C2' => 410, 'C3' => 13.5, 'C4' => 2.4, 'C5' => 120.3],
            ],
            [
                'kode_saham' => 'BMRI',
                'nama_perusahaan' => 'PT Bank Mandiri (Persero) Tbk',
                'sektor' => 'Keuangan',
                'nilai' => ['C1' => 20.1, 'C2' => 550, 'C3' => 11.8, 'C4' => 2.1, 'C5' => 95.0],
            ],
            [
                'kode_saham' => 'TLKM',
                'nama_perusahaan' => 'PT Telkom Indonesia (Persero) Tbk',
                'sektor' => 'Infrastruktur',
                'nilai' => ['C1' => 16.5, 'C2' => 245, 'C3' => 14.2, 'C4' => 2.6, 'C5' => 78.4],
            ],
            [
                'kode_saham' => 'ASII',
                'nama_perusahaan' => 'PT Astra International Tbk',
                'sektor' => 'Perindustrian',
                'nilai' => ['C1' => 15.2, 'C2' => 780, 'C3' => 7.2, 'C4' => 1.0, 'C5' => 45.2],
            ],
            [
                'kode_saham' => 'UNTR',
                'nama_perusahaan' => 'PT United Tractors Tbk',
                'sektor' => 'Perindustrian',
                'nilai' => ['C1' => 24.8, 'C2' => 5400, 'C3' => 5.1, 'C4' => 1.2, 'C5' => 15.6],
            ],
            [
                'kode_saham' => 'ICBP',
                'nama_perusahaan' => 'PT Indofood CBP Sukses Makmur Tbk',
                'sektor' => 'Barang Konsumen Primer',
                'nilai' => ['C1' => 18.0, 'C2' => 620, 'C3' => 17.5, 'C4' => 3.1, 'C5' => 22.8],
            ],
            [
                'kode_saham' => 'ADRO',
                'nama_perusahaan' => 'PT Adaro Energy Indonesia Tbk',
                'sektor' => 'Energi',
                'nilai' => ['C1' => 27.5, 'C2' => 890, 'C3' => 4.3, 'C4' => 0.9, 'C5' => 110.2],
            ],
            [
                'kode_saham' => 'BBNI',
                'nama_perusahaan' => 'PT Bank Negara Indonesia (Persero) Tbk',
                'sektor' => 'Keuangan',
                'nilai' => ['C1' => 15.6, 'C2' => 580, 'C3' => 9.4, 'C4' => 1.3, 'C5' => 55.7],
            ],
            [
                'kode_saham' => 'KLBF',
                'nama_perusahaan' => 'PT Kalbe Farma Tbk',
                'sektor' => 'Kesehatan',
                'nilai' => ['C1' => 14.1, 'C2' => 65, 'C3' => 23.0, 'C4' => 3.3, 'C5' => 35.0],
            ],
        ];

        // 4. Masukkan Data Saham dan Nilai Fundamental
        foreach ($sahams as $data) {
            $sahamModel = SahamAlternatif::updateOrCreate(
                ['kode_saham' => $data['kode_saham']],
                [
                    'nama_perusahaan' => $data['nama_perusahaan'],
                    'sektor' => $data['sektor'],
                ]
            );

            foreach ($data['nilai'] as $kodeKriteria => $nilaiAktual) {
                NilaiFundamental::updateOrCreate(
                    [
                        'saham_id' => $sahamModel->id,
                        'kriteria_id' => $kriteriaModels[$kodeKriteria]->id,
                    ],
                    [
                        'nilai_aktual' => $nilaiAktual,
                    ]
                );
            }
        }
    }
}
