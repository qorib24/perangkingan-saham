@extends('layouts.app', ['title' => 'Kuesioner Pembobotan AHP'])

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('investor.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
                <h3 class="fw-bold mb-1">Kuesioner Perbandingan Kriteria (AHP)</h3>
                <p class="text-muted mb-0">Tentukan preferensi tingkat kepentingan relatif antar rasio fundamental saham.</p>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="setPresetEqual()">
                <i class="bi bi-magic me-1"></i> Isi Otomatis (Sama Penting)
            </button>
        </div>

        <!-- Panduan Skala Saaty -->
        <div class="card card-custom p-4 mb-4 border-primary border-opacity-25" style="background: #f0f7ff;">
            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-info-circle-fill me-2"></i>Panduan Skala Nilai Saaty (1 - 9)</h6>
            <div class="row g-2 text-muted small">
                <div class="col-md-4"><strong>1:</strong> Kedua kriteria sama penting</div>
                <div class="col-md-4"><strong>3:</strong> Sedikit lebih penting</div>
                <div class="col-md-4"><strong>5:</strong> Lebih penting</div>
                <div class="col-md-4"><strong>7:</strong> Sangat lebih penting</div>
                <div class="col-md-4"><strong>9:</strong> Mutlak lebih penting</div>
                <div class="col-md-4"><em>2, 4, 6, 8:</em> Nilai antara / kompromi</div>
            </div>
        </div>

        <form action="{{ route('ahp.calculate') }}" method="POST" id="ahpForm">
            @csrf

            <!-- Form Perbandingan Berpasangan -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3">Tabel Perbandingan Berpasangan</h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30%;">Kriteria A</th>
                                <th class="text-center" style="width: 40%;">Skala Kepentingan (Saaty)</th>
                                <th class="text-end" style="width: 30%;">Kriteria B</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $kList = $kriterias->values();
                                $total = count($kList);
                            @endphp

                            @for ($i = 0; $i < $total; $i++)
                                @for ($j = $i + 1; $j < $total; $j++)
                                    @php
                                        $kA = $kList[$i];
                                        $kB = $kList[$j];
                                        $pairKey = $kA->id . '_' . $kB->id;
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold me-1">{{ $kA->kode }}</span>
                                            <span class="fw-semibold">{{ $kA->nama }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <select class="form-select form-select-sm pair-choice" 
                                                        data-ka="{{ $kA->id }}" 
                                                        data-kb="{{ $kB->id }}" 
                                                        id="choice_{{ $pairKey }}" 
                                                        style="max-width: 130px;">
                                                    <option value="A">{{ $kA->kode }} Lebih Penting</option>
                                                    <option value="E" selected>Sama Penting (1)</option>
                                                    <option value="B">{{ $kB->kode }} Lebih Penting</option>
                                                </select>

                                                <select class="form-select form-select-sm pair-value" 
                                                        data-ka="{{ $kA->id }}" 
                                                        data-kb="{{ $kB->id }}" 
                                                        id="val_{{ $pairKey }}" 
                                                        style="max-width: 100px;">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-semibold">{{ $kB->nama }}</span>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold ms-1">{{ $kB->kode }}</span>
                                        </td>
                                    </tr>
                                @endfor
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Hidden Inputs Container for Matrix Structure -->
            <div id="hiddenMatrixContainer"></div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
                <a href="{{ route('investor.dashboard') }}" class="btn btn-light px-4 py-2 rounded-3 me-md-2">Batal</a>
                <button type="submit" class="btn btn-primary-custom px-5 py-3 fs-6">
                    <i class="bi bi-cpu-fill me-2"></i> Hitung Bobot AHP & Proses CoCoSo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const kriteriaIds = @json($kriterias->pluck('id'));

    function generateMatrixInputs() {
        const container = document.getElementById('hiddenMatrixContainer');
        container.innerHTML = '';

        // Inisialisasi matriks identitas dasar [i][i] = 1
        const matrix = {};
        kriteriaIds.forEach(i => {
            matrix[i] = {};
            kriteriaIds.forEach(j => {
                matrix[i][j] = (i === j) ? 1 : null;
            });
        });

        // Ambil nilai perbandingan dari dropdown form
        document.querySelectorAll('.pair-choice').forEach(select => {
            const ka = select.dataset.ka;
            const kb = select.dataset.kb;
            const choice = select.value;
            const pairKey = ka + '_' + kb;
            const val = parseFloat(document.getElementById('val_' + pairKey).value);

            if (choice === 'E') {
                matrix[ka][kb] = 1;
                matrix[kb][ka] = 1;
            } else if (choice === 'A') {
                matrix[ka][kb] = val;
                matrix[kb][ka] = 1 / val;
            } else if (choice === 'B') {
                matrix[ka][kb] = 1 / val;
                matrix[kb][ka] = val;
            }
        });

        // Buat hidden inputs sesuai format Laravel: matrix[baris][kolom]
        kriteriaIds.forEach(i => {
            kriteriaIds.forEach(j => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `matrix[${i}][${j}]`;
                input.value = matrix[i][j];
                container.appendChild(input);
            });
        });
    }

    // Set preset nilai sama rata
    function setPresetEqual() {
        document.querySelectorAll('.pair-choice').forEach(select => select.value = 'E');
        document.querySelectorAll('.pair-value').forEach(select => select.value = '1');
    }

    // Event listener sebelum submit form
    document.getElementById('ahpForm').addEventListener('submit', function(e) {
        generateMatrixInputs();
    });

    // Otomatis disable skala jika Sama Penting dipilih
    document.querySelectorAll('.pair-choice').forEach(select => {
        select.addEventListener('change', function() {
            const pairKey = this.dataset.ka + '_' + this.dataset.kb;
            const valSelect = document.getElementById('val_' + pairKey);
            if (this.value === 'E') {
                valSelect.value = '1';
                valSelect.disabled = true;
            } else {
                valSelect.disabled = false;
                if(valSelect.value === '1') valSelect.value = '3';
            }
        });
    });

    // Trigger initial state
    document.querySelectorAll('.pair-choice').forEach(select => select.dispatchEvent(new Event('change')));
</script>
@endpush
