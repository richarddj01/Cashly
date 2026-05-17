<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoPersonal extends Model
{

    protected $table = 'movimientos_personales';

    protected $fillable = [
        'user_id',
        'cuenta_id',
        'categoria_id',
        'tipo',
        'direccion',
        'monto',
        'descripcion',
        'fecha',
        'estado',
        'notas',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha' => 'date',
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

    public function prestamo()
    {
        return $this->hasOne(Prestamo::class, 'movimiento_personal_id');
    }
}
