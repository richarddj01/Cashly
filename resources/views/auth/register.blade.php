<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly — Crear cuenta</title>
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
        .register-card {
            background: #fff;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
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
    <div class="register-card">

        <div class="text-center mb-4">
            <div class="brand">Cash<span>ly</span></div>
            <p class="text-muted small mt-1">Crea tu cuenta</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-medium">Nombre</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" autofocus autocomplete="name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Correo electrónico</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Contraseña</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Confirmar contraseña</label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                Crear cuenta
            </button>

        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-decoration-none">Inicia sesión</a>
        </p>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
