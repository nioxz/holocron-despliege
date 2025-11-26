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
    Schema::table('users', function (Blueprint $table) {
        // 1. Roles Específicos
        // Renombraremos 'role' a 'sst_role' mentalmente, pero para no romper código viejo,
        // usaremos 'role' para SST y creamos uno nuevo para Almacén.
        $table->string('warehouse_role')->default('user'); // 'admin' (Almacenero) o 'user' (Solicitante)

        // 2. Primer Ingreso
        $table->boolean('is_password_changed')->default(false); // ¿Ya cambió la clave temporal?
        $table->timestamp('terms_accepted_at')->nullable();     // ¿Cuándo firmó el contrato?
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
