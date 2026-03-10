<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Halal Bihalal') — Absensi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --halal-primary: #0d9488;
            --halal-primary-dark: #0f766e;
            --halal-gold: #d97706;
            --halal-gold-light: #fef3c7;
            --halal-cream: #f0fdf4;
            --halal-white: #ffffff;
            --halal-text: #1e293b;
            --halal-muted: #64748b;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(160deg, #f0fdf4 0%, #ecfdf5 30%, #fffbeb 100%);
            min-height: 100vh;
            color: var(--halal-text);
        }
        .navbar-halal {
            background: rgba(255,255,255,0.9) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 0 rgba(13, 148, 136, 0.08);
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--halal-primary-dark) !important;
            font-size: 1.2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .navbar-brand img {
            height: 36px;
            width: auto;
        }
        .nav-link {
            font-weight: 500;
            color: var(--halal-text) !important;
            border-radius: 0.5rem;
        }
        .nav-link:hover {
            color: var(--halal-primary) !important;
            background: var(--halal-cream);
        }
        .btn-halal {
            background: linear-gradient(135deg, var(--halal-primary) 0%, var(--halal-primary-dark) 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 14px rgba(13, 148, 136, 0.35);
        }
        .btn-halal:hover {
            background: linear-gradient(135deg, var(--halal-primary-dark) 0%, #0f766e 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.4);
            transform: translateY(-1px);
        }
        .card-halal {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(13, 148, 136, 0.08), 0 1px 3px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .card-halal .card-body {
            padding: 1.5rem 1.75rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--halal-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 148, 136, 0.2);
        }
        .alert-success { background: #d1fae5; border-color: #a7f3d0; color: #065f46; }
        .alert-warning { background: var(--halal-gold-light); border-color: #fcd34d; color: #92400e; }
        .alert-danger { background: #fee2e2; border-color: #fecaca; color: #991b1b; }
        .alert-info { background: #e0f2fe; border-color: #bae6fd; color: #0369a1; }
        .fw-600 { font-weight: 600; }
        .text-halal { color: var(--halal-primary-dark); }
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%230d9488' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
    @stack('styles')
</head>
<body class="bg-pattern">
    <nav class="navbar navbar-expand-lg navbar-light navbar-halal mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('attendance.index') }}">
                <img src="{{ asset('images/mitech-logo.png') }}" alt="MITech" class="d-inline-block">
                <span class="d-none d-sm-inline">Halal Bihalal</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('attendance.index') }}">Absensi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('employees.index') }}">Karyawan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('employees.import') }}">Import</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('attendances.index') }}">Rekap</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link text-danger p-0 ms-2 border-0 bg-transparent" style="font-weight: 500;">Keluar</button>
                            </form>
                        </li>
                    @else
                        <!-- <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('register') }}">Daftar</a>
                        </li> -->
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
