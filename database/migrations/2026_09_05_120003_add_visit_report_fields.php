<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitas', function (Blueprint $table) {
            $table->text('motivo_rechazo')->nullable()->after('estado');
            $table->foreignId('reportada_por')->nullable()->after('motivo_rechazo')->constrained('users')->nullOnDelete();
        });

        Schema::table('novedades', function (Blueprint $table) {
            $table->string('evidencia_path')->nullable()->after('resuelta_at');
            $table->string('evidencia_mime')->nullable()->after('evidencia_path');
        });
    }

    public function down(): void
    {
        Schema::table('visitas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reportada_por');
            $table->dropColumn('motivo_rechazo');
        });
        Schema::table('novedades', function (Blueprint $table) {
            $table->dropColumn(['evidencia_path', 'evidencia_mime']);
        });
    }
};