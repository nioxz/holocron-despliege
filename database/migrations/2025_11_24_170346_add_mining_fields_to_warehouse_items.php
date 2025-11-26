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
    Schema::table('warehouse_items', function (Blueprint $table) {
        $table->string('internal_code')->nullable()->after('id'); // Ej: SAP-1023
        $table->string('location')->nullable()->after('stock_minimo'); // Ej: Pasillo 4, Rack B
        $table->string('datasheet_path')->nullable()->after('imagen_path'); // Ficha técnica
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_items', function (Blueprint $table) {
            //
        });
    }
};
