<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribuidoraRecarga extends Model
{
    protected $table = 'distribuidoras_recarga';

    protected $fillable = [
        'user_id',
        'nombre',
        'saldo_actual',
        'saldo_minimo',
        'activa',
    ];

    protected $casts = [
        'saldo_actual' => 'decimal:2',
        'saldo_minimo' => 'decimal:2',
        'activa'       => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoRecarga::class, 'distribuidora_id');
    }

    public function tienesSaldoBajo(): bool
    {
        return $this->saldo_actual <= $this->saldo_minimo;
    }

    public function vender(float $monto, float $comision, string $telefono): MovimientoRecarga
    {
        if ($this->saldo_actual < $monto) {
            throw new \Exception("Saldo insuficiente con {$this->nombre}");
        }

        $nuevoSaldo = $this->saldo_actual - $monto;

        $movimiento = $this->movimientos()->create([
            'user_id'        => $this->user_id,
            'tipo'           => 'venta',
            'monto'          => $monto,
            'comision'       => $comision,
            'saldo_despues'  => $nuevoSaldo,
            'numero_destino' => $telefono,
            'fecha'          => today(),
        ]);

        $this->update(['saldo_actual' => $nuevoSaldo]);

        return $movimiento;
    }

    public function depositar(float $monto, int $cuentaId): MovimientoRecarga
    {
        $nuevoSaldo = $this->saldo_actual + $monto;

        $movimiento = $this->movimientos()->create([
            'user_id'       => $this->user_id,
            'tipo'          => 'deposito',
            'monto'         => $monto,
            'comision'      => 0,
            'saldo_despues' => $nuevoSaldo,
            'cuenta_id'     => $cuentaId,
            'fecha'         => today(),
        ]);

        $this->update(['saldo_actual' => $nuevoSaldo]);

        return $movimiento;
    }
}
