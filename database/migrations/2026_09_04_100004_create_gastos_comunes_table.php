<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodos_gastos_comunes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('condominio_id')->constrained()->cascadeOnDelete();
            $table->date('periodo');
            $table->decimal('total_gastos', 14, 2)->default(0);
            $table->enum('estado', ['borrador', 'emitido', 'cerrado'])->default('borrador');
            $table->timestamps();
            $table->unique(['condominio_id', 'periodo']);
        });

        Schema::create('cargos_gastos_comunes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periodo_gastos_comunes_id')->constrained('periodos_gastos_comunes')->cascadeOnDelete();
            $table->foreignId('unidad_id')->constrained('unidades')->cascadeOnDelete();
            $table->decimal('monto', 14, 2);
            $table->decimal('pagado', 14, 2)->default(0);
            $table->date('vencimiento')->nullable();
            $table->timestamps();
            $table->unique(['periodo_gastos_comunes_id', 'unidad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos_gastos_comunes');
        Schema::dropIfExists('periodos_gastos_comunes');
    }
};