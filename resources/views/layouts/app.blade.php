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

        /* ── Sidebar ─────────────────────────────── */
        #sidebar {
            min-height: 100vh;
            width: 250px;
            background-color: #1a1a2e;
            position: fixed;
            top: 0; left: 0;
            z-index: 1040;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        #sidebar .sidebar-brand {
            padding: 1.5rem 1rem;
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        #sidebar .sidebar-brand span { color: #4cc9f0; }

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

        #sidebar .nav-link i { width: 20px; margin-right: 8px; }

        #sidebar .sidebar-section {
            color: rgba(255,255,255,0.35);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 1rem 1.2rem 0.3rem;
        }

        /* ── Contenido principal ─────────────────── */
        #main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ── Cards ───────────────────────────────── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .card-stat { border-left: 4px solid; }

        /* ── Overlay para móvil ──────────────────── */
        #sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
        }

        #sidebar-overlay.show { display: block; }

        /* ── Topbar móvil ────────────────────────── */
        #topbar-mobile {
            display: none;
            background: #1a1a2e;
            color: #fff;
            padding: 0.8rem 1rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            align-items: center;
            justify-content: space-between;
        }

        #topbar-mobile .brand { font-size: 1.2rem; font-weight: 700; color: #fff; }
        #topbar-mobile .brand span { color: #4cc9f0; }

        /* ── Responsive ──────────────────────────── */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-250px);
            }

            #sidebar.open {
                transform: translateX(0);
            }

            #main-content {
                margin-left: 0;
                padding: 1rem;
            }

            #topbar-mobile {
                display: flex;
            }

            .card-stat {
                border-left: none;
                border-top: 4px solid;
            }

            /* Tablas en móvil */
            .table-responsive table thead {
                display: none;
            }

            .table-responsive table tr {
                display: block;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                margin-bottom: 0.5rem;
                padding: 0.5rem;
            }

            .table-responsive table td {
                display: flex;
                justify-content: space-between;
                border: none;
                padding: 3px 6px;
                font-size: 0.85rem;
            }

            .table-responsive table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #6c757d;
                margin-right: 8px;
                white-space: nowrap;
            }
        }
    </style>
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
    <meta name="theme-color" content="#1a1a2e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Cashly">
    @stack('styles')
</head>
<body>

<!-- Overlay para cerrar sidebar en móvil -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- Topbar móvil -->
<div id="topbar-mobile">
    <button onclick="openSidebar()"
            style="background:none;border:none;color:#fff;font-size:1.4rem;padding:0;">
        <i class="bi bi-list"></i>
    </button>
    <span class="brand">Cash<span>ly</span></span>
    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
         style="width:32px;height:32px;font-size:0.8rem;">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>
</div>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-brand d-flex justify-content-between align-items-center">
        <span>Cash<span style="color:#4cc9f0;">ly</span></span>
        <button onclick="closeSidebar()"
                class="d-md-none"
                style="background:none;border:none;color:rgba(255,255,255,0.5);font-size:1.2rem;">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="pt-2">
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="sidebar-section">Personal</div>
        <a href="{{ route('movimientos-personales.index') }}"
           class="nav-link {{ request()->routeIs('movimientos-personales.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-arrow-left-right"></i> Movimientos
        </a>
        <a href="{{ route('presupuestos.index') }}"
           class="nav-link {{ request()->routeIs('presupuestos.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-pie-chart"></i> Presupuestos
        </a>
        <a href="{{ route('metas-ahorro.index') }}"
           class="nav-link {{ request()->routeIs('metas-ahorro.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-piggy-bank"></i> Metas de ahorro
        </a>
        <a href="{{ route('deudas.index') }}"
           class="nav-link {{ request()->routeIs('deudas.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-credit-card"></i> Deudas
        </a>

        <div class="sidebar-section">Negocio</div>
        <a href="{{ route('movimientos-negocio.index') }}"
           class="nav-link {{ request()->routeIs('movimientos-negocio.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-shop"></i> Movimientos
        </a>
        <a href="{{ route('recargas.index') }}"
           class="nav-link {{ request()->routeIs('recargas.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-phone"></i> Recargas
        </a>
        <a href="{{ route('prestamos.index') }}"
           class="nav-link {{ request()->routeIs('prestamos.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-arrow-down-up"></i> Préstamos
        </a>

        <div class="sidebar-section">Configuración</div>
        <a href="{{ route('cuentas.index') }}"
           class="nav-link {{ request()->routeIs('cuentas.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-wallet2"></i> Cuentas
        </a>
        <a href="{{ route('categorias.index') }}"
           class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-tags"></i> Categorías
        </a>
        <a href="{{ route('empleados.index') }}"
           class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-people"></i> Empleados
        </a>
        <a href="{{ route('distribuidoras.index') }}"
           class="nav-link {{ request()->routeIs('distribuidoras.*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="bi bi-sim"></i> Distribuidoras
        </a>

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

    <!-- Topbar desktop -->
    <div class="d-none d-md-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-semibold text-dark">@yield('title', 'Dashboard')</h5>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small">{{ auth()->user()->name }}</span>
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                 style="width:32px;height:32px;font-size:0.8rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <!-- Título en móvil -->
    <h6 class="d-md-none fw-semibold mb-3">@yield('title', 'Dashboard')</h6>

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

    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sidebar móvil -->
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebar-overlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('show');
        document.body.style.overflow = '';
    }
</script>

<!-- Service Worker -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('SW registrado:', reg.scope))
                .catch(err => console.log('SW error:', err));
        });
    }
</script>

@stack('scripts')
</body>
</html>
