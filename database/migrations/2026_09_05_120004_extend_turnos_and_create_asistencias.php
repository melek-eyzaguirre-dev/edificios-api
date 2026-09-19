<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->enum('sistema', ['4x4', '6x1', '5x2', 'personalizado'])->default('personalizado')->after('tipo');
            $table->json('dias_trabajados')->nullable()->after('fin');
            $table->json('dias_libres')->nullable()->after('dias_trabajados');
            $table->date('vigente_desde')->nullable()->after('dias_libres');
            $table->date('vigente_hasta')->nullable()->after('vigente_desde');
        });

        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turno_id')->constrained('turnos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('fecha');
            $table->dateTime('entrada')->nullable();
            $table->dateTime('salida')->nullable();
            $table->unsignedSmallInteger('minutos_extra')->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->unique(['turno_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
        Schema::table('turnos', function (Blueprint $table) {
            $table->dropColumn(['sistema', 'dias_trabajados', 'dias_libres', 'vigente_desde', 'vigente_hasta']);
        });
    }
};