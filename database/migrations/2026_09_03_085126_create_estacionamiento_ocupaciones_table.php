<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estacionamiento_ocupaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estacionamiento_id')->constrained('estacionamientos')->cascadeOnDelete();
            $table->foreignId('visita_id')->nullable()->constrained('visitas')->nullOnDelete();
            $table->foreignId('unidad_id')->constrained('unidades')->cascadeOnDelete();
            $table->dateTime('inicio');
            $table->dateTime('fin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estacionamiento_ocupaciones');
    }
};