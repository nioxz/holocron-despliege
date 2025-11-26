<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseMovement extends Model
{
    use HasFactory;

    // AQUÍ ESTABA EL ERROR: Le damos permiso a estos campos exactos
    protected $fillable = [
        'item_id',       // ID del producto (antes warehouse_item_id)
        'user_id',       // Quién hizo el movimiento
        'request_id',    // ID del pedido (antes warehouse_request_id)
        'type',          // SALIDA o ENTRADA
        'quantity',      // Cantidad
        'reason'         // Razón (Despacho/Devolución)
    ];

    // Relación con el Producto
    public function item()
    {
        return $this->belongsTo(WarehouseItem::class, 'item_id');
    }

    // Relación con el Usuario (Almacenero)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el Pedido Original
    public function request()
    {
        return $this->belongsTo(WarehouseRequest::class, 'request_id');
    }
}