<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('condominio_id')->constrained('condominios')->cascadeOnDelete();
            $table->string('numero'); // ej: "302", "Casa 14"
            $table->string('torre')->nullable(); // si aplica
            $table->decimal('prorrateo', 8, 4)->nullable(); // % gasto común, útil para fase 2
            $table->timestamps();

            $table->unique(['condominio_id', 'torre', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
