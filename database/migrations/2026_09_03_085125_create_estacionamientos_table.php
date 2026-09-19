<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estacionamientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('condominio_id')->constrained('condominios')->cascadeOnDelete();
            $table->string('codigo'); // ej: "E-12"
            $table->enum('tipo', ['fijo', 'visita'])->default('fijo');
            $table->foreignId('unidad_id')->nullable()->constrained('unidades')->nullOnDelete(); // si es fijo, a qué unidad pertenece
            $table->boolean('disponible')->default(true); // si es de visita, si está libre ahora
            $table->timestamps();

            $table->unique(['condominio_id', 'codigo']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('estacionamiento_ocupaciones');
        Schema::dropIfExists('estacionamientos');
    }
};
