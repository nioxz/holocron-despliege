<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Models\WarehouseRequest;
use Illuminate\Support\Facades\Auth;

class MyRequests extends Component
{
    // El trabajador avisa que va a devolver el ítem
    public function startReturn($id)
    {
        $request = WarehouseRequest::where('user_id', Auth::id())->find($id);
        
        if ($request && $request->status === 'EN_PRESTAMO') {
            $request->update([
                'status' => 'EN_DEVOLUCION', // Nuevo estado intermedio
                'return_status' => 'ESPERANDO_ALMACEN'
            ]);
            session()->flash('message', 'Has notificado la devolución. Por favor, acércate al almacén para entregar el ítem.');
        }
    }

    public function render()
    {
        $requests = WarehouseRequest::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.warehouse.my-requests', compact('requests'))
            ->layout('layouts.warehouse');
    }
}