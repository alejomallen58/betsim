<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('partido_id')->constrained('partidos')->onDelete('cascade');
            $table->enum('tipo', ['local', 'empate', 'visitante']);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('cuota', 5, 2);         // cuota en el momento de apostar
            $table->decimal('ganancia_potencial', 10, 2);
            $table->enum('estado', ['pendiente', 'ganada', 'perdida', 'cancelada'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apuestas');
    }
};
