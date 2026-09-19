<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('condominio_id')->constrained()->cascadeOnDelete();
            $table->string('razon_social');
            $table->string('rut')->nullable();
            $table->string('contacto')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('categoria')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->index(['condominio_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};