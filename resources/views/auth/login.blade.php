@extends('layouts.app', ['title' => 'Login - SPK LQ45'])

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-6 col-lg-5">
        <div class="card card-custom p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 text-primary d-inline-flex p-3 rounded-circle mb-3">
                    <i class="bi bi-shield-lock-fill fs-2"></i>
                </div>
                <h4 class="fw-bold mb-1">Masuk ke Sistem</h4>
                <p class="text-muted small">Silakan masuk menggunakan akun Admin atau Investor Anda</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" id="emailInput" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kata Sandi (Password)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                        <input type="password" id="passwordInput" name="password" class="form-control border-start-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                    </div>
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label text-muted small" for="remember">Ingat Sesi Saya</label>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-2 mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
                </button>

                <div class="text-center">
                    <span class="text-muted small">Belum punya akun?</span>
                    <a href="{{ route('register') }}" class="text-primary fw-bold small text-decoration-none">Daftar Akun Investor</a>
                </div>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-4 pt-3 border-top">
                <div class="text-center text-muted small fw-bold mb-2">⚡ AKUN UJI COBA CEPAT (1-KLIK)</div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-dark w-50 rounded-3 py-2" onclick="fillCredentials('admin@lq45.com', 'password123')">
                        <i class="bi bi-person-fill-gear me-1"></i> Mode Admin
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary w-50 rounded-3 py-2" onclick="fillCredentials('investor@lq45.com', 'password123')">
                        <i class="bi bi-person-fill me-1"></i> Mode Investor
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function fillCredentials(email, password) {
        document.getElementById('emailInput').value = email;
        document.getElementById('passwordInput').value = password;
    }
</script>
@endpush
