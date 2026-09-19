<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('novedades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('condominio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('registrado_por')->constrained('users')->restrictOnDelete();
            $table->string('titulo');
            $table->text('descripcion');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->enum('estado', ['abierta', 'en_proceso', 'resuelta'])->default('abierta');
            $table->timestamp('resuelta_at')->nullable();
            $table->timestamps();
            $table->index(['condominio_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novedades');
    }
};