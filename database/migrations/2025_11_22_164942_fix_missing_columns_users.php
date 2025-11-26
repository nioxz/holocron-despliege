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
        // Agregamos las columnas que faltaban
        if (!Schema::hasColumn('users', 'warehouse_role')) {
            $table->string('warehouse_role')->default('user');
        }
        if (!Schema::hasColumn('users', 'is_password_changed')) {
            $table->boolean('is_password_changed')->default(false);
        }
        if (!Schema::hasColumn('users', 'terms_accepted_at')) {
            $table->timestamp('terms_accepted_at')->nullable();
        }
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
