<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estacionamientos', function (Blueprint $table) {
            $table->string('moneda', 3)->default('CLP')->after('disponible');
            $table->decimal('tarifa_hora', 12, 2)->default(0)->after('moneda');
            $table->unsignedSmallInteger('max_horas_visita')->default(4)->after('tarifa_hora');
        });

        Schema::create('feedback_residentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('condominio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unidad_id')->nullable()->constrained('unidades')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipo', ['reclamo', 'sugerencia', 'felicitacion']);
            $table->string('asunto');
            $table->text('mensaje');
            $table->enum('estado', ['recibido', 'en_revision', 'respondido', 'cerrado'])->default('recibido');
            $table->text('respuesta')->nullable();
            $table->timestamps();
            $table->index(['condominio_id', 'tipo', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_residentes');
        Schema::table('estacionamientos', function (Blueprint $table) {
            $table->dropColumn(['moneda', 'tarifa_hora', 'max_horas_visita']);
        });
    }
};