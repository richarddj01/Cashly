<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaNegocio extends Model
{
    protected $table = 'areas_negocio';

    protected $fillable = [
        'user_id',
        'nombre',
        'color',
        'icono',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoNegocio::class, 'area_id');
    }
}
