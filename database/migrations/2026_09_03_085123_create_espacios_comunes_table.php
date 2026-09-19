<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('espacios_comunes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('condominio_id')->constrained('condominios')->cascadeOnDelete();
            $table->string('nombre'); // ej: "Quincho", "Sala de eventos"
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('capacidad')->nullable();
            $table->unsignedInteger('duracion_maxima_horas')->default(4);
            $table->unsignedInteger('anticipacion_minima_horas')->default(24);
            $table->decimal('valor_garantia', 10, 0)->nullable(); // opcional, fase 2 pagos
            $table->boolean('requiere_aprobacion')->default(false); // true = admin aprueba, false = auto-confirmado
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('espacios_comunes');
    }
};
