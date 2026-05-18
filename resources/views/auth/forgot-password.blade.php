<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly — Recuperar contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .brand { font-size: 2rem; font-weight: 700; color: #1a1a2e; }
        .brand span { color: #4cc9f0; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="brand">Cash<span>ly</span></div>
            <p class="text-muted small mt-1">Recupera tu contraseña</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success small">{{ session('status') }}</div>
        @endif

        <p class="text-muted small mb-4">
            Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-medium">Correo electrónico</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">
                Enviar enlace
            </button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            <a href="{{ route('login') }}" class="text-decoration-none">Volver al inicio de sesión</a>
        </p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
