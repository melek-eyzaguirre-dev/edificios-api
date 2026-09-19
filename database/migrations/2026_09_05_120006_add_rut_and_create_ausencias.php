<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('rut', 20)->nullable()->unique()->after('email');
        });

        Schema::create('solicitudes_ausencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('revisada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', ['licencia_medica', 'permiso', 'vacaciones', 'inasistencia_justificada']);
            $table->date('desde');
            $table->date('hasta');
            $table->text('motivo');
            $table->string('documento_path')->nullable();
            $table->string('documento_mime')->nullable();
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_ausencia');
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['rut']);
            $table->dropColumn('rut');
        });
    }
};