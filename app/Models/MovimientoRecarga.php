<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoRecarga extends Model
{
    protected $table = 'movimientos_recarga';

    protected $fillable = [
        'user_id',
        'distribuidora_id',
        'cuenta_id',
        'tipo',
        'monto',
        'comision',
        'saldo_despues',
        'numero_destino',
        'fecha',
        'notas',
    ];

    protected $casts = [
        'monto'         => 'decimal:2',
        'comision'      => 'decimal:2',
        'saldo_despues' => 'decimal:2',
        'fecha'         => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function distribuidora()
    {
        return $this->belongsTo(DistribuidoraRecarga::class, 'distribuidora_id');
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }
}
