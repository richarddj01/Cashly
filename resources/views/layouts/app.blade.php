<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Cashly') }} — @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { background-color: #f8f9fa; }

        /* Sidebar */
        #sidebar {
            min-height: 100vh;
            width: 250px;
            background-color: #1a1a2e;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: all 0.3s;
        }

        #sidebar .sidebar-brand {
            padding: 1.5rem 1rem;
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        #sidebar .sidebar-brand span {
            color: #4cc9f0;
        }

        #sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            margin: 2px 8px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(76, 201, 240, 0.15);
        }

        #sidebar .nav-link i {
            width: 20px;
            margin-right: 8px;
        }

        #sidebar .sidebar-section {
            color: rgba(255,255,255,0.35);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 1rem 1.2rem 0.3rem;
        }

        /* Contenido principal */
        #main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .card-stat {
            border-left: 4px solid;
        }

        @media (max-width: 768px) {
            #sidebar { margin-left: -250px; }
            #sidebar.active { margin-left: 0; }
            #main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-brand">
            Cash<span>ly</span>
        </div>

        <div class="pt-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <!-- Personal -->
            <div class="sidebar-section">Personal</div>
            <a href="{{ route('movimientos-personales.index') }}"
               class="nav-link {{ request()->routeIs('movimientos-personales.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i> Movimientos
            </a>
            <a href="{{ route('presupuestos.index') }}"
               class="nav-link {{ request()->routeIs('presupuestos.*') ? 'active' : '' }}">
                <i class="bi bi-pie-chart"></i> Presupuestos
            </a>
            <a href="{{ route('metas-ahorro.index') }}"
               class="nav-link {{ request()->routeIs('metas-ahorro.*') ? 'active' : '' }}">
                <i class="bi bi-piggy-bank"></i> Metas de ahorro
            </a>
            <a href="{{ route('deudas.index') }}"
               class="nav-link {{ request()->routeIs('deudas.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i> Deudas
            </a>

            <!-- Negocio -->
            <div class="sidebar-section">Negocio</div>
            <a href="{{ route('movimientos-negocio.index') }}"
               class="nav-link {{ request()->routeIs('movimientos-negocio.*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i> Movimientos
            </a>
            <a href="{{ route('recargas.index') }}"
               class="nav-link {{ request()->routeIs('recargas.*') ? 'active' : '' }}">
                <i class="bi bi-phone"></i> Recargas
            </a>
            <a href="{{ route('prestamos.index') }}"
               class="nav-link {{ request()->routeIs('prestamos.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-down-up"></i> Préstamos
            </a>

            <!-- Configuración -->
            <div class="sidebar-section">Configuración</div>
            <a href="{{ route('cuentas.index') }}"
               class="nav-link {{ request()->routeIs('cuentas.*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i> Cuentas
            </a>
            <a href="{{ route('categorias.index') }}"
               class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Categorías
            </a>
            <a href="{{ route('empleados.index') }}"
               class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Empleados
            </a>
            <a href="{{ route('distribuidoras.index') }}"
               class="nav-link {{ request()->routeIs('distribuidoras.*') ? 'active' : '' }}">
                <i class="bi bi-sim"></i> Distribuidoras
            </a>

            <!-- Usuario -->
            <div class="sidebar-section">Cuenta</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div id="main-content">

        <!-- Topbar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 fw-semibold text-dark">@yield('title', 'Dashboard')</h5>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">{{ auth()->user()->name }}</span>
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                     style="width:32px;height:32px;font-size:0.8rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Contenido de cada página -->
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
