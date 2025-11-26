<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_requests', function (Blueprint $table) {
            $table->id();
            
            // Quién pide y de qué empresa
            $table->foreignId('user_id')->constrained();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            
            // Lista de ítems pedidos (formato JSON)
            $table->json('items'); 
            
            // Estado (Pendiente, Aprobado, Rechazado, Entregado)
            $table->string('status')->default('Pendiente');
            
            // Quién atendió el pedido (Almacenero)
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->text('comments')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_requests');
    }
};