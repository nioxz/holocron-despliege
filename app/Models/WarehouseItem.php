<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WarehouseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_code', // Nuevo: Código SAP/Interno
        'nombre', 
        'descripcion', 
        'unidad', 
        'categoria',
        'stock_actual', 
        'stock_minimo', 
        'location',      // Nuevo: Ubicación
        'imagen_path', 
        'datasheet_path', // Nuevo: Ficha técnica
        'company_id',
        'item_type'
    ];

    protected $casts = [
        'stock_actual' => 'integer',
        'stock_minimo' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('company', function (Builder $builder) {
            if (Auth::check() && Auth::user()->company_id) {
                $builder->where('company_id', Auth::user()->company_id);
            }
        });

        static::creating(function ($item) {
            if (Auth::check() && Auth::user()->company_id) {
                $item->company_id = Auth::user()->company_id;
            }
        });
    }
}