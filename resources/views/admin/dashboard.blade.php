@extends('layouts.app', ['title' => 'Dashboard Admin - Master Data'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard Administrator</h3>
        <p class="text-muted mb-0">Kelola data master kriteria, saham LQ45, dan nilai fundamental.</p>
    </div>
    <span class="badge bg-dark px-3 py-2 rounded-pill"><i class="bi bi-shield-lock-fill me-1"></i> Admin Privileges</span>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 small text-uppercase fw-bold">Total Kriteria</div>
                    <div class="display-5 fw-bold my-1">{{ $totalKriteria }}</div>
                    <small class="text-white-50">Indikator Fundamental Pasar</small>
                </div>
            </div>
            <i class="bi bi-card-checklist icon-bg"></i>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #059669, #047857);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 small text-uppercase fw-bold">Total Saham LQ45</div>
                    <div class="display-5 fw-bold my-1">{{ $totalSaham }}</div>
                    <small class="text-white-50">Emiten Terdaftar</small>
                </div>
            </div>
            <i class="bi bi-building icon-bg"></i>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #7c3aed, #6d28d9);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 small text-uppercase fw-bold">Riwayat Analisis</div>
                    <div class="display-5 fw-bold my-1">{{ $totalAnalisis }}</div>
                    <small class="text-white-50">Sesi Analisis Investor</small>
                </div>
            </div>
            <i class="bi bi-clock-history icon-bg"></i>
        </div>
    </div>
</div>

<!-- Pintasan Cepat Navigasi -->
<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-custom h-100 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                    <i class="bi bi-card-checklist fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Kelola Kriteria</h5>
                    <small class="text-muted">Master Indikator</small>
                </div>
            </div>
            <p class="text-muted small mb-4">
                Ubah atau tambah kriteria rasio fundamental (ROE, EPS, PER, PBV) serta tentukan sifat Benefit atau Cost.
            </p>
            <a href="{{ route('admin.kriteria.index') }}" class="btn btn-outline-primary mt-auto rounded-3">
                Buka Menu Kriteria <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom h-100 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-4">
                    <i class="bi bi-building fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Saham Alternatif</h5>
                    <small class="text-muted">Daftar Emiten</small>
                </div>
            </div>
            <p class="text-muted small mb-4">
                Kelola daftar saham indeks LQ45 lengkap dengan kode saham emiten, nama perseroan, dan sektor industri.
            </p>
            <a href="{{ route('admin.saham.index') }}" class="btn btn-outline-success mt-auto rounded-3">
                Buka Menu Saham <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom h-100 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-4">
                    <i class="bi bi-table fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Nilai Fundamental</h5>
                    <small class="text-muted">Matrix Data Finansial</small>
                </div>
            </div>
            <p class="text-muted small mb-4">
                Input dan perbarui data nilai aktual masing-masing kriteria untuk seluruh 45 saham yang terdaftar.
            </p>
            <a href="{{ route('admin.nilai_fundamental.index') }}" class="btn btn-outline-warning text-dark mt-auto rounded-3">
                Kelola Nilai Fundamental <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
@endsection
