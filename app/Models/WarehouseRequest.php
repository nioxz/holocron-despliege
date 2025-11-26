<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WarehouseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'company_id', 
        'items', 
        'status', 
        'processed_by', 
        'comments',
        'work_area', 
        'return_status' 
    ];

    protected $casts = [
        'items' => 'array', 
    ];

    // Relación 1: Solicitante
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relación 2: PROCESADOR (Almacenero/Supervisor) <-- ESTO ARREGLA EL ERROR
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // --- SEGURIDAD MULTI-EMPRESA ---
    protected static function booted()
    {
        static::addGlobalScope('company', function (Builder $builder) {
            if (Auth::check() && Auth::user()->company_id) {
                $builder->where('company_id', Auth::user()->company_id);
            }
        });

        static::creating(function ($req) {
            if (Auth::check() && Auth::user()->company_id) {
                $req->company_id = Auth::user()->company_id;
            }
        });
    }
}