@extends('layouts.app', ['title' => 'Daftar Akun Investor - SPK LQ45'])

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-6 col-lg-5">
        <div class="card card-custom p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="bg-success bg-opacity-10 text-success d-inline-flex p-3 rounded-circle mb-3">
                    <i class="bi bi-person-plus-fill fs-2"></i>
                </div>
                <h4 class="fw-bold mb-1">Daftar Akun Investor</h4>
                <p class="text-muted small">Buat akun untuk menganalisis dan menyimpan rekomendasi portofolio Anda</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror" placeholder="Nama Anda" value="{{ old('name') }}" required autofocus>
                    </div>
                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kata Sandi (Password)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                    </div>
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Konfirmasi Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-muted"></i></span>
                        <input type="password" name="password_confirmation" class="form-control border-start-0" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-2 mb-3">
                    <i class="bi bi-check2-circle me-2"></i> Daftar Sekarang
                </button>

                <div class="text-center">
                    <span class="text-muted small">Sudah memiliki akun?</span>
                    <a href="{{ route('login') }}" class="text-primary fw-bold small text-decoration-none">Masuk di Sini</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
