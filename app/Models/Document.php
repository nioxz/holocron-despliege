<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id', // <--- IMPORTANTE: Agregado para multi-empresa
        'type',
        'status',
        'content',
        'supervisor_comments',
        'supervisor_id',
    ];

    // Convertir JSON automáticamente
    protected $casts = [
        'content' => 'array',
    ];
    
    // Relación: El documento pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: El documento fue revisado por un supervisor
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // Relación: El documento pertenece a una empresa
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // --- MAGIA MULTI-EMPRESA ---
    protected static function booted()
    {
        // 1. Al leer: Solo mostrar documentos de MI empresa
        static::addGlobalScope('company', function ($builder) {
            if (auth()->check() && auth()->user()->company_id) {
                $builder->where('company_id', auth()->user()->company_id);
            }
        });

        // 2. Al crear: Asignar automáticamente MI empresa al documento
        static::creating(function ($document) {
            if (auth()->check() && auth()->user()->company_id) {
                $document->company_id = auth()->user()->company_id;
            }
        });
    }
}