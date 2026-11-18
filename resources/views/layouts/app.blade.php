<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SPK Portofolio Saham LQ45' }} - AHP & CoCoSo</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --secondary-color: #0f172a;
            --accent-color: #10b981;
            --bg-light: #f8fafc;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #fff !important;
        }
        .navbar-brand span {
            color: #38bdf8;
        }
        .nav-link {
            font-weight: 500;
            color: #cbd5e1 !important;
            transition: all 0.2s;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.1);
        }
        .card-custom {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            background: #ffffff;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-custom:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
        }
        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .stat-card .icon-bg {
            position: absolute;
            right: -10px;
            bottom: -15px;
            font-size: 5rem;
            opacity: 0.15;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 0.6rem 1.4rem;
            transition: all 0.2s;
        }
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
            color: white;
            transform: translateY(-1px);
        }
        .table-custom thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.78rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 1rem;
        }
        .table-custom tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .badge-benefit {
            background-color: #dcfce7;
            color: #166534;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
        }
        .badge-cost {
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
        }
        .badge-rank {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        .rank-1 { background-color: #fef08a; color: #854d0e; box-shadow: 0 0 10px rgba(250, 204, 21, 0.5); }
        .rank-2 { background-color: #e2e8f0; color: #475569; }
        .rank-3 { background-color: #fed7aa; color: #9a3412; }
        footer {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1.5rem 0;
            color: #64748b;
            font-size: 0.875rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('investor.dashboard') }}">
                <i class="bi bi-graph-up-arrow text-info fs-4"></i>
                <div>
                    SPK Saham <span>LQ45</span>
                    <div style="font-size: 0.65rem; font-weight: 400; opacity: 0.7;">Metode AHP & CoCoSo</div>
                </div>
            </a>
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-2 text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <!-- Menu Admin -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-grid me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.kriteria.*') ? 'active' : '' }}" href="{{ route('admin.kriteria.index') }}">
                                    <i class="bi bi-card-checklist me-1"></i> Kriteria
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.saham.*') ? 'active' : '' }}" href="{{ route('admin.saham.index') }}">
                                    <i class="bi bi-building me-1"></i> Saham
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.nilai_fundamental.*') ? 'active' : '' }}" href="{{ route('admin.nilai_fundamental.index') }}">
                                    <i class="bi bi-table me-1"></i> Fundamental
                                </a>
                            </li>
                        @else
                            <!-- Menu Investor -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('investor.dashboard') ? 'active' : '' }}" href="{{ route('investor.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('investor.kuesioner') ? 'active' : '' }}" href="{{ route('investor.kuesioner') }}">
                                    <i class="bi bi-sliders me-1"></i> Analisis Portofolio
                                </a>
                            </li>
                        @endif

                        <div class="vr d-none d-lg-block mx-2 bg-secondary opacity-50" style="height: 25px;"></div>

                        <!-- User Profile Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                                <span class="badge {{ Auth::user()->role === 'admin' ? 'bg-danger' : 'bg-primary' }} text-uppercase px-2 py-1">
                                    {{ Auth::user()->role }}
                                </span>
                                <span class="text-white fw-semibold small">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2">
                                <li class="px-3 py-2 text-muted small border-bottom">
                                    <div>Masuk sebagai:</div>
                                    <div class="fw-bold text-dark">{{ Auth::user()->email }}</div>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Guest Menu -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-sm btn-primary-custom" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Daftar Investor
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3" role="alert">
                    <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                    <div>
                        <strong>Sukses!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                    <div>
                        <strong>Peringatan!</strong> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="mb-1 fw-semibold text-dark">Sistem Pendukung Keputusan Pemilihan Portofolio Saham LQ45</p>
            <p class="mb-0 text-muted">Kombinasi Algoritma Analytical Hierarchy Process (AHP) & Combined Compromise Solution (CoCoSo)</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
