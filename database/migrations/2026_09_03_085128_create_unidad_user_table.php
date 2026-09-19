<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidad_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidad_id')->constrained('unidades')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipo', ['propietario', 'arrendatario', 'familiar'])->default('propietario');
            $table->timestamps();

            $table->unique(['unidad_id', 'user_id']);
        });

        // Conserjes y admins de condominio: a qué condominio(s) tienen acceso
        Schema::create('condominio_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('condominio_id')->constrained('condominios')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['condominio_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('condominio_user');
        Schema::dropIfExists('unidad_user');
    }
};
