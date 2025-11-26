<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    // Campos que permitimos guardar en la base de datos
    protected $fillable = [
        'user_id',          // Quién dio el examen
        'announcement_id',  // De qué anuncio fue el examen
        'score',            // Nota (0-100)
        'passed'            // ¿Aprobó? (true/false)
    ];

    // Relación: El intento pertenece a un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: El intento pertenece a un Anuncio
    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}