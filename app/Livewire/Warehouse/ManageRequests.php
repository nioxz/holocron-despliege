<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Models\WarehouseRequest;
use App\Models\WarehouseItem;
use App\Models\WarehouseMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageRequests extends Component
{
    public $showActionModal = false;
    public $currentRequestId;
    public $comment = '';
    public $actionType = ''; 

    // VARIABLES PARA REPORTE KARDEX (NUEVO)
    public $dateFrom;
    public $dateTo;

    public function mount()
    {
        // Seguridad
        if (Auth::user()->warehouse_role !== 'admin' && Auth::user()->role !== 'supervisor') {
            abort(403, 'Acceso denegado.');
        }

        // Inicializar fechas (Mes actual por defecto)
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->endOfMonth()->format('Y-m-d');
    }

    // FUNCIÓN PARA DESCARGAR REPORTE FILTRADO (NUEVO)
    public function downloadReport()
    {
        return redirect()->route('warehouse.kardex', [
            'from' => $this->dateFrom,
            'to' => $this->dateTo
        ]);
    }

    public function openActionModal($id, $type)
    {
        $this->currentRequestId = $id;
        $this->actionType = $type;
        $this->comment = ''; 
        $this->showActionModal = true;
    }

    public function processRequest()
    {
        if (Auth::user()->warehouse_role !== 'admin' && Auth::user()->role !== 'supervisor') abort(403);
        
        if ($this->actionType === 'reject') {
            $this->validate(['comment' => 'required|min:5']);
        }
        
        try {
            $request = WarehouseRequest::find($this->currentRequestId);
            
            if (!$request || $request->status !== 'Pendiente') {
                throw new \Exception("Pedido ya procesado.");
            }
            
            if ($this->actionType === 'approve') {
                $request->update([
                    'status' => 'POR_RECOGER',
                    'processed_by' => Auth::id(),
                    'comments' => $this->comment
                ]);
                session()->flash('success', 'Solicitud aprobada. Esperando recojo.');

            } elseif ($this->actionType === 'reject') {
                $request->update([
                    'status' => 'RECHAZADO',
                    'processed_by' => Auth::id(),
                    'comments' => $this->comment
                ]);
                session()->flash('error', 'Solicitud rechazada.');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }

        $this->showActionModal = false;
        $this->reset(['currentRequestId', 'comment', 'actionType']);
    }

    public function confirmDelivery($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $request = WarehouseRequest::find($id);
                if ($request->status !== 'POR_RECOGER') return;

                foreach ($request->items as $item) {
                    $product = WarehouseItem::find($item['id']);
                    if ($product) {
                        if ($product->stock_actual < $item['qty']) throw new \Exception("Stock insuficiente: " . $product->nombre);
                        $product->decrement('stock_actual', $item['qty']);

                        WarehouseMovement::create([
                            'item_id' => $product->id,
                            'user_id' => Auth::id(),
                            'request_id' => $request->id,
                            'type' => 'SALIDA',
                            'quantity' => $item['qty'],
                            'reason' => 'Despacho Pedido #' . $request->id
                        ]);
                    }
                }

                $firstItem = $request->items[0] ?? [];
                $isReturnable = ($firstItem['item_type'] ?? 'consumable') === 'returnable';

                $request->update([
                    'status' => $isReturnable ? 'EN_PRESTAMO' : 'ENTREGADO',
                    'return_status' => $isReturnable ? 'PENDIENTE_DEVOLUCION' : 'N/A',
                ]);
            });

            session()->flash('success', 'Entrega confirmada y stock descontado.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function markAsReturned($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $request = WarehouseRequest::find($id);
                if (!in_array($request->status, ['EN_PRESTAMO', 'EN_DEVOLUCION'])) return;

                foreach ($request->items as $item) {
                    $product = WarehouseItem::find($item['id']);
                    if ($product) {
                        $product->increment('stock_actual', $item['qty']);

                        WarehouseMovement::create([
                            'item_id' => $product->id,
                            'user_id' => Auth::id(),
                            'request_id' => $request->id,
                            'type' => 'ENTRADA',
                            'quantity' => $item['qty'],
                            'reason' => 'Devolución Pedido #' . $request->id
                        ]);
                    }
                }

                $request->update([
                    'status' => 'DEVUELTO',
                    'return_status' => 'DEVUELTO_OK'
                ]);
            });

            session()->flash('success', 'Devolución registrada. Stock recuperado.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $requests = WarehouseRequest::with(['user', 'processor'])
            ->orderByRaw("FIELD(status, 'Pendiente', 'EN_DEVOLUCION', 'POR_RECOGER', 'EN_PRESTAMO', 'ENTREGADO', 'DEVUELTO', 'RECHAZADO')")
            ->orderBy('created_at', 'desc') 
            ->get();

        return view('livewire.warehouse.manage-requests', compact('requests'))
            ->layout('layouts.warehouse');
    }
}