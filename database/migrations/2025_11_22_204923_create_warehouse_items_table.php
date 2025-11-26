<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_items', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');          // Ej: Casco 3M Blanco
            $table->text('descripcion')->nullable();
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(5); // Para alertas
            $table->string('unidad');          // Ej: Unidad, Par, Caja
            $table->string('categoria');       // Ej: EPP, Herramientas
            $table->string('imagen_path')->nullable();
            
            // CLAVE: Pertenece a una empresa específica
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_items');
    }
};