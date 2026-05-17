<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DistribuidoraRecargaController;
use App\Http\Controllers\MovimientoNegocioController;
use App\Http\Controllers\MovimientoRecargaController;
use App\Http\Controllers\MovimientoPersonalController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\MetaAhorroController;
use App\Http\Controllers\DeudaController;
use App\Http\Controllers\PrestamoController;

// Redirige la página principal al dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Todas estas rutas requieren estar autenticado
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Configuración
    Route::resource('cuentas', CuentaController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('empleados', EmpleadoController::class);

    // Negocio
    Route::resource('movimientos-negocio', MovimientoNegocioController::class);
    Route::resource('prestamos', PrestamoController::class);

    // Recargas
    Route::resource('distribuidoras', DistribuidoraRecargaController::class);
    Route::resource('recargas', MovimientoRecargaController::class);

    // Personal
    Route::resource('movimientos-personales', MovimientoPersonalController::class);
    Route::resource('presupuestos', PresupuestoController::class);
    Route::resource('metas-ahorro', MetaAhorroController::class);
    Route::resource('deudas', DeudaController::class);

});

require __DIR__.'/auth.php';
