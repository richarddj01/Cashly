<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaAhorro extends Model
{
    protected $table = 'metas_ahorro';

    protected $fillable = [
        'user_id',
        'cuenta_id',
        'nombre',
        'monto_objetivo',
        'monto_actual',
        'fecha_limite',
        'estado',
        'notas',
    ];

    protected $casts = [
        'monto_objetivo' => 'decimal:2',
        'monto_actual'   => 'decimal:2',
        'fecha_limite'   => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function getPorcentajeAttribute(): float
    {
        if ($this->monto_objetivo == 0) return 0;
        return round(($this->monto_actual / $this->monto_objetivo) * 100, 1);
    }

    public function getFaltaAttribute(): float
    {
        return max(0, $this->monto_objetivo - $this->monto_actual);
    }
}
