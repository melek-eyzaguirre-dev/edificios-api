<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('condominios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administradora_id')->constrained('administradoras')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('comuna')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('condominios');
    }
};
