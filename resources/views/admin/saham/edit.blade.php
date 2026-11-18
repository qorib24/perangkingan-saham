@extends('layouts.app', ['title' => 'Edit Saham Alternatif'])

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.saham.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Saham
                </a>
                <h3 class="fw-bold mb-1">Edit Saham: {{ $saham->kode_saham }}</h3>
            </div>
        </div>

        <div class="card card-custom p-4">
            <form action="{{ route('admin.saham.update', $saham->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Saham (Ticker)</label>
                    <input type="text" name="kode_saham" class="form-control text-uppercase @error('kode_saham') is-invalid @enderror" value="{{ old('kode_saham', $saham->kode_saham) }}" required maxlength="10">
                    @error('kode_saham') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Perusahaan / Emiten</label>
                    <input type="text" name="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror" value="{{ old('nama_perusahaan', $saham->nama_perusahaan) }}" required>
                    @error('nama_perusahaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Sektor Industri</label>
                    <input type="text" name="sektor" class="form-control @error('sektor') is-invalid @enderror" value="{{ old('sektor', $saham->sektor) }}" required>
                    @error('sektor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.saham.index') }}" class="btn btn-light px-4">Batal</a>
                    <button type="submit" class="btn btn-primary-custom px-4">Perbarui Saham</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
