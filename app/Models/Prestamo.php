<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $fillable = [
        'user_id',
        'direccion',
        'monto',
        'motivo',
        'fecha',
        'fecha_devolucion',
        'estado',
        'movimiento_personal_id',
        'movimiento_negocio_id',
        'notas',
    ];

    protected $casts = [
        'monto'            => 'decimal:2',
        'fecha'            => 'date',
        'fecha_devolucion' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movimientoPersonal()
    {
        return $this->belongsTo(MovimientoPersonal::class, 'movimiento_personal_id');
    }

    public function movimientoNegocio()
    {
        return $this->belongsTo(MovimientoNegocio::class, 'movimiento_negocio_id');
    }
}
