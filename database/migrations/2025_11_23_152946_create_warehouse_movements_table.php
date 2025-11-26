<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void {
    Schema::create('warehouse_movements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('warehouse_item_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained(); // Quién hizo la acción (Almacenero)
        $table->foreignId('warehouse_request_id')->nullable()->constrained()->onDelete('set null');
        $table->string('type'); // 'SALIDA' o 'ENTRADA'
        $table->integer('quantity');
        $table->string('reason')->nullable(); // Ej: "Entrega de pedido #45"
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_movements');
    }
};
