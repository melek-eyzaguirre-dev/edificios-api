<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('condominio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipo', ['diurno', 'nocturno', 'fin_de_semana', 'personalizado'])->default('diurno');
            $table->dateTime('inicio');
            $table->dateTime('fin');
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->index(['condominio_id', 'inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};