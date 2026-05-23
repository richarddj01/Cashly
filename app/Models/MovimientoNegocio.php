<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoNegocio extends Model
{
    protected $table = 'movimientos_negocio';

    protected $fillable = [
        'user_id',
        'cuenta_id',
        'categoria_id',
        'empleado_id',
        'area_id',
        'tipo',
        'direccion',
        'monto',
        'descripcion',
        'fecha',
        'estado',
        'fecha_vencimiento',
        'beneficiario',
        'notas',
    ];

    protected $casts = [
        'monto'             => 'decimal:2',
        'fecha'             => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function area()
    {
        return $this->belongsTo(AreaNegocio::class, 'area_id');
    }

    public function prestamo()
    {
        return $this->hasOne(Prestamo::class, 'movimiento_negocio_id');
    }
}
