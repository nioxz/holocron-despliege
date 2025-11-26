<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('announcements', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->text('contenido'); // El texto de la noticia
        $table->string('tipo'); // 'Noticia', 'Alerta', 'Charla', 'Reconocimiento'
        $table->string('imagen_path')->nullable(); // Foto opcional
        $table->string('archivo_path')->nullable(); // PDF opcional
        $table->foreignId('user_id')->constrained(); // Quién lo publicó
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
