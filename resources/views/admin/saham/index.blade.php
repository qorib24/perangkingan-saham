@extends('layouts.app', ['title' => 'Saham Alternatif LQ45 - Master Data'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
            <i class="bi bi-arrow-left me-1"></i> Dashboard Admin
        </a>
        <h3 class="fw-bold mb-1">Daftar Saham Alternatif LQ45</h3>
        <p class="text-muted mb-0">Kelola emiten yang terdaftar sebagai alternatif pilihan portofolio.</p>
    </div>
    <a href="{{ route('admin.saham.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i> Tambah Saham
    </a>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 120px;">Kode Saham</th>
                    <th>Nama Perusahaan</th>
                    <th>Sektor</th>
                    <th class="text-end" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sahams as $saham)
                <tr>
                    <td class="fw-bold fs-6 text-primary">{{ $saham->kode_saham }}</td>
                    <td class="fw-semibold text-dark">{{ $saham->nama_perusahaan }}</td>
                    <td>
                        <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">{{ $saham->sektor }}</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.saham.edit', $saham->id) }}" class="btn btn-sm btn-outline-secondary rounded-2">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.saham.destroy', $saham->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus saham {{ $saham->kode_saham }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-2">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data saham terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
