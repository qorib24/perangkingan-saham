@extends('layouts.app', ['title' => 'Input Nilai Fundamental'])

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.nilai_fundamental.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Matriks
                </a>
                <h3 class="fw-bold mb-1">Nilai Fundamental: {{ $saham->kode_saham }}</h3>
                <p class="text-muted mb-0">{{ $saham->nama_perusahaan }} ({{ $saham->sektor }})</p>
            </div>
        </div>

        <div class="card card-custom p-4">
            <form action="{{ route('admin.nilai_fundamental.update', $saham->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    @foreach($kriterias as $k)
                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-flex justify-content-between">
                            <span>{{ $k->kode }} - {{ $k->nama }}</span>
                            <span class="{{ $k->atribut == 'benefit' ? 'badge-benefit' : 'badge-cost' }}" style="font-size: 0.7rem;">
                                {{ strtoupper($k->atribut) }}
                            </span>
                        </label>
                        <div class="input-group">
                            <input type="number" step="any" name="nilai[{{ $k->id }}]" class="form-control" 
                                   value="{{ old('nilai.'.$k->id, isset($nilai[$k->id]) ? $nilai[$k->id]->nilai_aktual : '') }}" 
                                   placeholder="Masukkan angka aktual" required>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.nilai_fundamental.index') }}" class="btn btn-light px-4">Batal</a>
                    <button type="submit" class="btn btn-primary-custom px-4">Simpan Nilai Fundamental</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
