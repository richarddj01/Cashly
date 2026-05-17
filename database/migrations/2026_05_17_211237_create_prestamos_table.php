<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // De dónde sale el dinero y a dónde va
            $table->enum('direccion', [
                'personal_a_negocio', // sacas de tu bolsillo para el negocio
                'negocio_a_personal', // el negocio te devuelve o te presta
            ]);

            $table->decimal('monto', 10, 2);
            $table->string('motivo')->nullable();       // "Pago proveedor", "Cubrir caja", etc.
            $table->date('fecha');
            $table->date('fecha_devolucion')->nullable(); // cuándo se planea devolver
            $table->enum('estado', ['pendiente', 'devuelto', 'cancelado'])->default('pendiente');

            // Referencias a los movimientos espejo
            $table->foreignId('movimiento_personal_id')->nullable()->constrained('movimientos_personales')->nullOnDelete();
            $table->foreignId('movimiento_negocio_id')->nullable()->constrained('movimientos_negocio')->nullOnDelete();

            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
