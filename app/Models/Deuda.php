<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    protected $fillable = [
        'user_id',
        'acreedor',
        'monto_total',
        'monto_pagado',
        'interes',
        'fecha_inicio',
        'fecha_vencimiento',
        'tipo',
        'estado',
        'notas',
    ];

    protected $casts = [
        'monto_total'       => 'decimal:2',
        'monto_pagado'      => 'decimal:2',
        'interes'           => 'decimal:2',
        'fecha_inicio'      => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSaldoPendienteAttribute(): float
    {
        return $this->monto_total - $this->monto_pagado;
    }

    public function getPorcentajeAttribute(): float
    {
        if ($this->monto_total == 0) return 0;
        return round(($this->monto_pagado / $this->monto_total) * 100, 1);
    }
}
