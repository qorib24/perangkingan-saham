@extends('layouts.app', ['title' => 'Kelola Kriteria - Master Data'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
            <i class="bi bi-arrow-left me-1"></i> Dashboard Admin
        </a>
        <h3 class="fw-bold mb-1">Daftar Kriteria Fundamental</h3>
        <p class="text-muted mb-0">Kelola kriteria yang digunakan untuk analisis perbandingan AHP dan CoCoSo.</p>
    </div>
    <a href="{{ route('admin.kriteria.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kriteria
    </a>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 80px;">Kode</th>
                    <th>Nama Kriteria</th>
                    <th>Atribut (Sifat)</th>
                    <th class="text-end" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kriterias as $k)
                <tr>
                    <td>
                        <span class="badge bg-primary text-white fw-bold px-3 py-2 rounded-3">{{ $k->kode }}</span>
                    </td>
                    <td class="fw-bold text-dark">{{ $k->nama }}</td>
                    <td>
                        <span class="{{ $k->atribut == 'benefit' ? 'badge-benefit' : 'badge-cost' }}">
                            <i class="bi {{ $k->atribut == 'benefit' ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }} me-1"></i>
                            {{ strtoupper($k->atribut) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.kriteria.edit', $k->id) }}" class="btn btn-sm btn-outline-secondary rounded-2">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.kriteria.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
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
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data kriteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
