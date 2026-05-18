<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly — Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .brand {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a2e;
        }
        .brand span { color: #4cc9f0; }
    </style>
</head>
<body>
    <div class="login-card">

        <div class="text-center mb-4">
            <div class="brand">Cash<span>ly</span></div>
            <p class="text-muted small mt-1">Inicia sesión en tu cuenta</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success small">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-medium">Correo electrónico</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" autofocus autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label class="form-label fw-medium">Contraseña</label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-muted">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label small text-muted" for="remember">
                    Recordarme
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                Iniciar sesión
            </button>

        </form>

        @if(Route::has('register'))
            <p class="text-center text-muted small mt-4 mb-0">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-decoration-none">Regístrate</a>
            </p>
        @endif

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
