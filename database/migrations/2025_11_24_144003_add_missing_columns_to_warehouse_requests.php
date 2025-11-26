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
    Schema::table('warehouse_requests', function (Blueprint $table) {
        // Agregamos las columnas que faltan si no existen
        if (!Schema::hasColumn('warehouse_requests', 'work_area')) {
            $table->string('work_area')->nullable()->after('status');
        }
        if (!Schema::hasColumn('warehouse_requests', 'return_status')) {
            $table->string('return_status')->default('N/A')->after('work_area');
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_requests', function (Blueprint $table) {
            //
        });
    }
};
