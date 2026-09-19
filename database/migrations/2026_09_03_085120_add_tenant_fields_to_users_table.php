<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // super_admin: dueño del sistema (tú)
            // admin_administradora: gestiona varios condominios de una administradora
            // admin_condominio: gestiona un condominio
            // conserje: opera portería de uno o más condominios
            // residente: usuario final
            $table->string('rol')->default('residente')->after('email');
            $table->foreignId('administradora_id')->nullable()->after('rol')
                ->constrained('administradoras')->nullOnDelete();
            $table->string('telefono')->nullable()->after('administradora_id');
            $table->string('foto_path')->nullable()->after('telefono');
            $table->boolean('activo')->default(true)->after('foto_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('administradora_id');
            $table->dropColumn(['rol', 'telefono', 'foto_path', 'activo']);
        });
    }
};
