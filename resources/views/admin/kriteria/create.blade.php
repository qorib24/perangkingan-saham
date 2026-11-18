@extends('layouts.app', ['title' => 'Tambah Kriteria'])

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.kriteria.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kriteria
                </a>
                <h3 class="fw-bold mb-1">Tambah Kriteria Baru</h3>
            </div>
        </div>

        <div class="card card-custom p-4">
            <form action="{{ route('admin.kriteria.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Kriteria</label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" placeholder="Contoh: C1, C2" value="{{ old('kode') }}" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kriteria</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: Return on Equity (ROE)" value="{{ old('nama') }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Atribut Sifat</label>
                    <select name="atribut" class="form-select @error('atribut') is-invalid @enderror" required>
                        <option value="benefit" {{ old('atribut') == 'benefit' ? 'selected' : '' }}>Benefit (Semakin tinggi semakin baik)</option>
                        <option value="cost" {{ old('atribut') == 'cost' ? 'selected' : '' }}>Cost (Semakin rendah semakin baik)</option>
                    </select>
                    @error('atribut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.kriteria.index') }}" class="btn btn-light px-4">Batal</a>
                    <button type="submit" class="btn btn-primary-custom px-4">Simpan Kriteria</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
