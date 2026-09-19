<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidad_id')->constrained('unidades')->cascadeOnDelete();
            $table->foreignId('autorizado_por')->nullable()->constrained('users')->nullOnDelete(); // residente que autorizó
            $table->foreignId('registrado_por')->nullable()->constrained('users')->nullOnDelete(); // conserje de turno
            $table->string('nombre_visitante');
            $table->string('rut_visitante')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('codigo_qr')->nullable()->unique(); // token único para autorización previa
            $table->dateTime('autorizado_desde')->nullable();
            $table->dateTime('autorizado_hasta')->nullable();
            $table->dateTime('hora_ingreso')->nullable();
            $table->dateTime('hora_salida')->nullable();
            $table->enum('estado', ['pendiente', 'autorizada', 'en_edificio', 'finalizada', 'rechazada'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};
