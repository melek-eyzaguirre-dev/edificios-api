<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('espacio_comun_id')->constrained('espacios_comunes')->cascadeOnDelete();
            $table->foreignId('unidad_id')->constrained('unidades')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // quien reserva
            $table->dateTime('inicio');
            $table->dateTime('fin');
            $table->enum('estado', ['pendiente', 'confirmada', 'rechazada', 'cancelada'])->default('pendiente');
            $table->text('motivo_rechazo')->nullable();
            $table->timestamps();

            // Evita solapamiento de reservas en un mismo espacio (se refuerza también a nivel de app)
            $table->index(['espacio_comun_id', 'inicio', 'fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
