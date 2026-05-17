<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    // Columnas que se pueden llenar masivamente
    protected $fillable = [
        'user_id',
        'nombre',
        'tipo',
        'contexto',
        'saldo_inicial',
        'activa',
    ];

    // Conversiones automáticas de tipos
    protected $casts = [
        'saldo_inicial' => 'decimal:2',
        'activa'        => 'boolean',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movimientosNegocio()
    {
        return $this->hasMany(MovimientoNegocio::class);
    }

    public function movimientosPersonales()
    {
        return $this->hasMany(MovimientoPersonal::class);
    }

    public function movimientosRecarga()
    {
        return $this->hasMany(MovimientoRecarga::class);
    }

    // Calcula el saldo actual sumando/restando movimientos
    public function getSaldoActualAttribute(): float
    {
        $entradasNegocio = $this->movimientosNegocio()
            ->where('direccion', 'entrada')
            ->where('estado', 'pagado')
            ->sum('monto');

        $salidasNegocio = $this->movimientosNegocio()
            ->where('direccion', 'salida')
            ->where('estado', 'pagado')
            ->sum('monto');

        $entradasPersonal = $this->movimientosPersonales()
            ->where('direccion', 'entrada')
            ->where('estado', 'pagado')
            ->sum('monto');

        $salidasPersonal = $this->movimientosPersonales()
            ->where('direccion', 'salida')
            ->where('estado', 'pagado')
            ->sum('monto');

        return $this->saldo_inicial
            + $entradasNegocio - $salidasNegocio
            + $entradasPersonal - $salidasPersonal;
    }
}
