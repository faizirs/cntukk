<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Aplikasi SPP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->

    <style>
        body {
            background-color: #383851;
            color: #ffffff;
        }

        .navbar {
            background-color: #1e1e2f;
        }

        .navbar-brand, .nav-link, .dropdown-item {
            color: #ddd !important;
        }

        .nav-link:hover, .dropdown-item:hover {
            color: #fff !important;
        }

        .active-link {
            color: #fff !important;
            font-weight: bold;
        }

        .card {
            background-color: #1f1f2e;
            color: white;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container-content {
            padding: 30px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Aplikasi SPP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                @if (auth()->user()->role == "admin")
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.index') ? 'active-link' : '' }}" href="{{ route('admin.index') }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pembayaran*') ? 'active-link' : '' }}" href="{{ route('pembayaran.index') }}">
                            Pembayaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kompetensi-keahlian*') ? 'active-link' : '' }}" href="{{ route('kompetensi-keahlian.index') }}">
                            Kompetensi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kelas*') ? 'active-link' : '' }}" href="{{ route('kelas.index') }}">
                            Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('spp*') ? 'active-link' : '' }}" href="{{ route('spp.index') }}">
                            SPP
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kelola-siswa*') ? 'active-link' : '' }}" href="{{ route('kelola-siswa.index') }}">
                            Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('management*') ? 'active-link' : '' }}" href="{{ route('management.index') }}">
                            Management
                        </a>
                    </li>
                @elseif(auth()->user()->role == "siswa")
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('siswa*') ? 'active-link' : '' }}" href="{{ route('siswa.index') }}">
                            Riwayat Pembayaran
                            </a>
                    </li>
                @endif
            </ul>

            <form action="{{ route('logout') }}" method="POST" class="d-flex">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="container-content">
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
