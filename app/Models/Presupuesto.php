<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $fillable = [
        'user_id',
        'categoria_id',
        'monto_limite',
        'mes',
        'anio',
    ];

    protected $casts = [
        'monto_limite' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Cuánto se ha gastado de este presupuesto
    public function getGastadoAttribute(): float
    {
        return MovimientoPersonal::where('user_id', $this->user_id)
            ->where('categoria_id', $this->categoria_id)
            ->where('direccion', 'salida')
            ->where('estado', 'pagado')
            ->whereMonth('fecha', $this->mes)
            ->whereYear('fecha', $this->anio)
            ->sum('monto');
    }

    // Cuánto queda disponible
    public function getDisponibleAttribute(): float
    {
        return $this->monto_limite - $this->gastado;
    }

    // Porcentaje usado
    public function getPorcentajeAttribute(): float
    {
        if ($this->monto_limite == 0) return 0;
        return round(($this->gastado / $this->monto_limite) * 100, 1);
    }
}
