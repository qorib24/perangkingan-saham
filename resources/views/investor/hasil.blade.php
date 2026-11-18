@extends('layouts.app', ['title' => 'Hasil Rekomendasi Portofolio Saham LQ45'])

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-8">
        <a href="{{ route('investor.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
        <h3 class="fw-bold mb-1">Hasil Rekomendasi Portofolio Saham LQ45</h3>
        <p class="text-muted mb-0">
            Dihitung pada: {{ date('d F Y, H:i', strtotime($riwayat->created_at)) }} WIB
        </p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <div class="d-inline-flex align-items-center gap-2 p-2 px-3 bg-white border rounded-pill shadow-sm">
            <span class="text-muted small">Consistency Ratio (CR):</span>
            <span class="badge {{ $riwayat->cr_value <= 0.1 ? 'bg-success' : 'bg-warning text-dark' }} fs-6">
                {{ round($riwayat->cr_value, 4) }}
            </span>
            <i class="bi bi-check-circle-fill text-success" title="Matriks Konsisten (CR <= 0.1)"></i>
        </div>
    </div>
</div>

<!-- Top 5 Rekomendasi & Chart Visualisasi -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card card-custom h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-trophy-fill text-warning me-2"></i>Top 5 Saham Rekomendasi
                </h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">Metode CoCoSo</span>
            </div>
            <p class="text-muted small mb-4">Urutan prioritas saham dengan skor kompromi ($K_i$) tertinggi untuk portofolio Anda:</p>
            
            <div class="list-group list-group-flush">
                @foreach($rankings->take(5) as $r)
                <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge-rank rank-{{ $r->ranking <= 3 ? $r->ranking : 'other bg-light text-dark border' }}">
                            {{ $r->ranking }}
                        </span>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $r->saham->kode_saham }}</h6>
                            <small class="text-muted">{{ $r->saham->nama_perusahaan }} • <span class="badge bg-light text-secondary border">{{ $r->saham->sektor }}</span></small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-5 text-primary">{{ round($r->skor_ki, 4) }}</div>
                        <small class="text-muted" style="font-size: 0.75rem;">Skor Kompromi ($K_i$)</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Grafik Visualisasi Chart.js -->
    <div class="col-lg-6">
        <div class="card card-custom h-100 p-4">
            <h5 class="fw-bold mb-3">Grafik Skor 5 Saham Terbaik</h5>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="topStockChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Transparansi Perhitungan Bobot AHP -->
<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2 text-info"></i>Bobot Prioritas Kriteria Fundamental (AHP)</h5>
    <div class="row g-3">
        @foreach($riwayat->detailBobots as $b)
        <div class="col-md">
            <div class="p-3 bg-light rounded-3 text-center border">
                <span class="badge {{ $b->kriteria->atribut == 'benefit' ? 'badge-benefit' : 'badge-cost' }} mb-2">
                    {{ $b->kriteria->kode }} ({{ ucfirst($b->kriteria->atribut) }})
                </span>
                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">{{ $b->kriteria->nama }}</h6>
                <div class="fs-4 fw-bold text-primary">{{ round($b->bobot_prioritas * 100, 2) }}%</div>
                <small class="text-muted">Bobot: {{ round($b->bobot_prioritas, 4) }}</small>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Tabel Lengkap Semua Ranking -->
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Tabel Lengkap Peringkat Saham Alternatif</h5>
        <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $rankings->count() }} Saham</span>
    </div>
    
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th class="text-center" style="width: 80px;">Peringkat</th>
                    <th>Kode Saham</th>
                    <th>Nama Perusahaan</th>
                    <th>Sektor</th>
                    <th class="text-end">Skor Akhir ($K_i$)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rankings as $item)
                <tr class="{{ $item->ranking <= 3 ? 'table-warning bg-opacity-10' : '' }}">
                    <td class="text-center">
                        <span class="badge-rank {{ $item->ranking <= 3 ? 'rank-'.$item->ranking : 'bg-light text-dark border' }}">
                            {{ $item->ranking }}
                        </span>
                    </td>
                    <td class="fw-bold text-dark fs-6">{{ $item->saham->kode_saham }}</td>
                    <td class="fw-semibold">{{ $item->saham->nama_perusahaan }}</td>
                    <td><span class="badge bg-light text-secondary border">{{ $item->saham->sektor }}</span></td>
                    <td class="text-end fw-bold fs-6 text-primary">{{ round($item->skor_ki, 5) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const topLabels = @json($rankings->take(5)->pluck('saham.kode_saham'));
    const topScores = @json($rankings->take(5)->pluck('skor_ki'));

    const ctx = document.getElementById('topStockChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: topLabels,
            datasets: [{
                label: 'Skor Kompromi (Ki)',
                data: topScores,
                backgroundColor: [
                    'rgba(37, 99, 235, 0.85)',
                    'rgba(59, 130, 246, 0.85)',
                    'rgba(96, 165, 250, 0.85)',
                    'rgba(147, 197, 253, 0.85)',
                    'rgba(191, 219, 254, 0.85)'
                ],
                borderColor: [
                    '#1d4ed8',
                    '#2563eb',
                    '#3b82f6',
                    '#60a5fa',
                    '#93c5fd'
                ],
                borderWidth: 1.5,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
