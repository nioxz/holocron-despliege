<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    // 1. Aquí agregamos 'quiz_data' a la lista de campos permitidos
    protected $fillable = [
        'titulo', 
        'contenido', 
        'tipo', 
        'imagen_path', 
        'archivo_path', 
        'user_id',
        'quiz_data' // <--- NUEVO: Para guardar las preguntas del examen
    ];

    // 2. Esto es MAGIA: Le dice a Laravel que 'quiz_data' es un Array, no texto.
    // Así no tienes que usar json_decode() manualmente.
    protected $casts = [
        'quiz_data' => 'array',
    ];

    // Relación: Un anuncio pertenece a un usuario (quien lo creó)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relación: Un anuncio puede tener muchos intentos de examen
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}