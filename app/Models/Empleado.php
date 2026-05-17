<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'cargo',
        'salario',
        'fecha_ingreso',
        'activo',
    ];

    protected $casts = [
        'salario'       => 'decimal:2',
        'fecha_ingreso' => 'date',
        'activo'        => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoNegocio::class);
    }
}
