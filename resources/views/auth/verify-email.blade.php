<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashly — Verificar correo</title>
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
            max-width: 420px;
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
            <p class="text-muted small mt-1">Verifica tu correo electrónico</p>
        </div>

        <p class="text-muted small mb-3">
            Gracias por registrarte. Antes de continuar, verifica tu correo haciendo clic en el enlace que te enviamos.
        </p>

        @if(session('status') == 'verification-link-sent')
            <div class="alert alert-success small">
                Se envió un nuevo enlace de verificación a tu correo.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-primary w-100 py-2">
                Reenviar correo de verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100">
                Cerrar sesión
            </button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
