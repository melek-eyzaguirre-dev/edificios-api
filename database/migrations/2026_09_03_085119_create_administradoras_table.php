<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administradoras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rut')->nullable()->unique(); // ajustar según país
            $table->string('email_contacto')->nullable();
            $table->string('telefono_contacto')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administradoras');
    }
};
