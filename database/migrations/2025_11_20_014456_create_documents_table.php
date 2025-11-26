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
    Schema::create('documents', function (Blueprint $table) {
        $table->id();
        
        // Relación: El documento pertenece a un usuario
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Tipo de documento (IPERC o ATS)
        $table->string('type'); 
        
        // Estado del documento (Por defecto 'En espera')
        $table->string('status')->default('En espera');
        
        // Aquí guardaremos toda la info del formulario (JSON es perfecto para datos flexibles)
        // Ejemplo: lugar, actividad, participantes, riesgos, epps, etc.
        $table->json('content'); 
        
        // Campos para el supervisor
        $table->text('supervisor_comments')->nullable();
        $table->foreignId('supervisor_id')->nullable()->constrained('users'); // Quién lo aprobó
        
        $table->timestamps(); // Guarda fecha de creación y actualización automáticamente
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
