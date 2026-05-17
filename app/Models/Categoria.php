<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'tipo',
        'contexto',
        'color',
        'icono',
    ];

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

    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class);
    }
}
