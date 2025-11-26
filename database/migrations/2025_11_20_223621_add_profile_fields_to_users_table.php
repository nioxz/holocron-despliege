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
        $table->string('surname')->after('name')->nullable(); // Apellidos
        $table->string('dni')->after('surname')->nullable();
        $table->date('birthdate')->after('dni')->nullable();
        $table->string('job_title')->after('birthdate')->nullable(); // Cargo
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
