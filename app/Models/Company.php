<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    // Campos permitidos para guardar
    protected $fillable = [
        'name', 
        'ruc', 
        'slug', 
        'logo_path'
    ];

    // --- RELACIONES ---

    // Una empresa TIENE MUCHOS usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Una empresa TIENE MUCHOS documentos (IPERC, ATS, etc.)
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}