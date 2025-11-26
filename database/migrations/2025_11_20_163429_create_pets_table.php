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
    Schema::create('pets', function (Blueprint $table) {
        $table->id();
        $table->string('titulo'); // Nombre del procedimiento
        $table->string('archivo_path'); // Ruta donde se guarda el PDF
        $table->foreignId('user_id')->constrained(); // Quién lo subió
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
