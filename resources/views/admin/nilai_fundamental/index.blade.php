@extends('layouts.app', ['title' => 'Nilai Fundamental Saham - Master Data'])

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
            <i class="bi bi-arrow-left me-1"></i> Dashboard Admin
        </a>
        <h3 class="fw-bold mb-1">Matriks Nilai Fundamental Saham</h3>
        <p class="text-muted mb-0">Kelola dan perbarui data rasio fundamental seluruh saham LQ45.</p>
    </div>
    
    <!-- Action Buttons -->
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.nilai_fundamental.download_template') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-download me-1"></i> Unduh Format Template
        </a>
        <button type="button" class="btn btn-success rounded-3 text-white" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel-fill me-1"></i> Import Excel / CSV
        </button>
        <form action="{{ route('admin.nilai_fundamental.delete_all') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS SEMUA DATA fundamental yang ada? Tindakan ini tidak dapat dibatalkan.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger rounded-3" {{ $totalNilai == 0 ? 'disabled' : '' }}>
                <i class="bi bi-trash3-fill me-1"></i> Kosongkan / Hapus Semua Data
            </button>
        </form>
    </div>
</div>

<!-- Status Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom p-3 bg-white">
            <div class="d-flex flex-wrap justify-content-between align-items-center px-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                        <i class="bi bi-database-check fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Status Data Fundamental Saat Ini</h6>
                        <small class="text-muted">Total terisi: <strong>{{ $totalNilai }}</strong> nilai pada <strong>{{ $sahams->count() }}</strong> emiten saham.</small>
                    </div>
                </div>
                <div>
                    @if($totalNilai > 0)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">
                            <i class="bi bi-check2-circle me-1"></i> Data Tersedia untuk Perhitungan
                        </span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">
                            <i class="bi bi-exclamation-circle me-1"></i> Data Masih Kosong
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold" id="importModalLabel">
                    <i class="bi bi-file-earmark-excel-fill text-success me-2"></i> Import Nilai Fundamental Massal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.nilai_fundamental.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Unggah file spreadsheet <strong>.xlsx</strong>, <strong>.xls</strong>, atau <strong>.csv</strong> untuk mengisi atau memperbarui data seluruh 45 saham secara instan.
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File Spreadsheet</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx, .xls, .csv" required>
                    </div>

                    <!-- Opsi Hapus Data Lama -->
                    <div class="p-3 bg-light rounded-3 border border-warning border-opacity-50 mb-3">
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" role="switch" id="hapusDataLama" name="hapus_data_lama" value="1" checked>
                            <label class="form-check-label fw-bold text-dark" for="hapusDataLama">
                                Hapus & bersihkan data fundamental lama
                            </label>
                        </div>
                        <small class="text-muted d-block ps-4">
                            Centang ini untuk mengosongkan nilai yang ada sebelumnya sebelum memasukkan nilai dari file baru.
                        </small>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.nilai_fundamental.download_template') }}" class="text-decoration-none small text-primary">
                            <i class="bi bi-question-circle me-1"></i> Butuh format contoh? Unduh di sini.
                        </a>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Mulai Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Matrix Table -->
<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 100px;">Kode</th>
                    <th>Nama Saham</th>
                    @foreach($kriterias as $k)
                        <th class="text-center">
                            {{ $k->kode }}
                            <div style="font-size: 0.68rem; font-weight: normal; text-transform: none;" class="text-muted">
                                ({{ ucfirst($k->atribut) }})
                            </div>
                        </th>
                    @endforeach
                    <th class="text-end" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sahams as $saham)
                @php
                    $nilaiMap = $saham->nilaiFundamentals->keyBy('kriteria_id');
                @endphp
                <tr>
                    <td class="fw-bold text-primary">{{ $saham->kode_saham }}</td>
                    <td class="fw-semibold text-dark">{{ $saham->nama_perusahaan }}</td>
                    @foreach($kriterias as $k)
                        <td class="text-center font-monospace">
                            @if(isset($nilaiMap[$k->id]))
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ $nilaiMap[$k->id]->nilai_aktual }}
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger small"><em>Kosong</em></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-end">
                        <a href="{{ route('admin.nilai_fundamental.edit', $saham->id) }}" class="btn btn-sm btn-outline-primary rounded-2">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 3 + $kriterias->count() }}" class="text-center py-4 text-muted">
                        Belum ada data saham terdaftar. Silakan import atau input manual.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
