@extends('layouts.app', ['title' => 'Dashboard Investor - SPK LQ45'])

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom border-0 text-white p-4 p-md-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);">
            <div class="position-relative z-1" style="max-width: 650px;">
                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3">
                    <i class="bi bi-stars me-1"></i> Smart Stock Portfolio Decision
                </span>
                <h1 class="display-6 fw-bold mb-3">Temukan Portofolio Saham LQ45 Terbaik Secara Presisi</h1>
                <p class="lead opacity-90 mb-4" style="font-size: 1.05rem;">
                    Gunakan metode AHP untuk menentukan preferensi risiko dan fundamental Anda, dipadukan dengan CoCoSo untuk merangking saham terbaik secara objektif.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('investor.kuesioner') }}" class="btn btn-light text-primary fw-bold px-4 py-3 rounded-3 shadow">
                        <i class="bi bi-play-circle-fill me-2 fs-5 align-middle"></i> Mulai Analisis Portofolio Sekarang
                    </a>
                </div>
            </div>
            <i class="bi bi-graph-up position-absolute end-0 bottom-0 text-white opacity-10 d-none d-md-block" style="font-size: 18rem; transform: translate(15%, 20%);"></i>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Info Metode AHP -->
    <div class="col-md-6">
        <div class="card card-custom h-100 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                    <i class="bi bi-pie-chart-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Metode AHP (Pembobotan)</h5>
                    <small class="text-muted">Analytic Hierarchy Process</small>
                </div>
            </div>
            <p class="text-muted mb-0">
                Membantu Anda mengukur tingkat kepentingan relatif antar rasio fundamental (seperti ROE vs PER vs PBV) melalui perbandingan berpasangan (Skala Saaty 1-9) dengan validasi konsistensi matematis (CR $\le$ 0.1).
            </p>
        </div>
    </div>

    <!-- Info Metode CoCoSo -->
    <div class="col-md-6">
        <div class="card card-custom h-100 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-4">
                    <i class="bi bi-award-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Metode CoCoSo (Perangkingan)</h5>
                    <small class="text-muted">Combined Compromise Solution</small>
                </div>
            </div>
            <p class="text-muted mb-0">
                Menggabungkan model penjumlahan bobot (*comparability sequence*) dan perkalian eksponensial (*power weight*) untuk menghasilkan skor kompromi ($K_i$) yang stabil dan objektif dalam merangking saham.
            </p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Kriteria Fundamental Aktif -->
    <div class="col-lg-5">
        <div class="card card-custom h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Kriteria Fundamental</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $kriterias->count() }} Kriteria</span>
            </div>
            <p class="text-muted small mb-3">Indikator pasar yang digunakan sebagai acuan evaluasi saham LQ45:</p>
            <div class="list-group list-group-flush border-top">
                @foreach($kriterias as $k)
                <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold text-dark">{{ $k->kode }} - {{ $k->nama }}</span>
                    </div>
                    <span class="{{ $k->atribut == 'benefit' ? 'badge-benefit' : 'badge-cost' }}">
                        {{ strtoupper($k->atribut) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Riwayat Analisis Pengguna -->
    <div class="col-lg-7">
        <div class="card card-custom h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Riwayat Analisis Terakhir</h5>
                <a href="{{ route('investor.kuesioner') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                    <i class="bi bi-plus-lg me-1"></i> Baru
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nilai CR</th>
                            <th>Status Konsistensi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayats as $r)
                        <tr>
                            <td class="fw-semibold text-secondary">
                                <i class="bi bi-calendar3 me-1"></i> {{ date('d M Y', strtotime($r->tanggal)) }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-bold">{{ round($r->cr_value, 4) }}</span>
                            </td>
                            <td>
                                @if($r->cr_value <= 0.1)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-semibold">
                                        <i class="bi bi-check-circle me-1"></i> Konsisten
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-semibold">
                                        <i class="bi bi-x-circle me-1"></i> Tidak Konsisten
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('hasil.show', $r->id) }}" class="btn btn-sm btn-primary-custom">
                                    <i class="bi bi-eye me-1"></i> Hasil
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                Belum ada riwayat analisis. Silakan klik tombol <strong>Mulai Analisis Portofolio</strong>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
