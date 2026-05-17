<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_recarga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('distribuidora_id')->constrained('distribuidoras_recarga')->cascadeOnDelete();
            $table->foreignId('cuenta_id')->nullable()->constrained()->nullOnDelete(); // de qué cuenta salió el depósito

            $table->enum('tipo', [
                'deposito',  // recargas saldo con la distribuidora
                'venta',     // vendes una recarga a un cliente
                'ajuste',    // corrección manual de saldo
            ]);

            $table->decimal('monto', 10, 2);              // valor de la recarga o depósito
            $table->decimal('comision', 10, 2)->default(0); // ganancia por la venta
            $table->decimal('saldo_despues', 10, 2);      // saldo tras el movimiento
            $table->string('numero_destino')->nullable();  // teléfono recargado
            $table->date('fecha');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_recarga');
    }
};
