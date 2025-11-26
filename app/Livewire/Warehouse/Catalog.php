<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\WarehouseItem;
use App\Models\WarehouseRequest;
use Illuminate\Support\Facades\Auth;

class Catalog extends Component
{
    use WithFileUploads;

    public $search = '';
    public $categoryFilter = '';
    
    // Variables de Solicitud
    public $isRequesting = false;
    public ?WarehouseItem $currentItem = null;
    public $requestQty = 1;
    public $workArea = '';
    public $justification = ''; 

    // Variables Admin (Gestión de Productos)
    public $isManaging = false;
    
    // AQUÍ ESTÁN LAS VARIABLES NUEVAS Y CORREGIDAS
    public $item_id;
    public $internal_code; // Nuevo: Código SAP/Interno
    public $nombre;
    public $descripcion;
    public $stock_actual;
    public $stock_minimo = 5;
    public $unidad = 'Unidad';
    public $categoria = 'EPP';
    public $item_type = 'consumable';
    public $location;      // Nuevo: Ubicación
    
    public $imagen;        // CORREGIDO: Usaremos '$imagen' siempre
    public $datasheet;     // Nuevo: Ficha técnica (PDF/Img)

    protected $rules = [
        'nombre' => 'required|min:3',
        'stock_actual' => 'required|numeric|min:0',
        'unidad' => 'required',
        'categoria' => 'required',
        'item_type' => 'required',
        'imagen' => 'nullable|image|max:2048',
        'datasheet' => 'nullable|file|max:5120' // Max 5MB
    ];

    public function getIsAdminProperty()
    {
        return Auth::user()->warehouse_role === 'admin' || Auth::user()->role === 'supervisor';
    }

    // --- LISTAS MAESTRAS (MEJORADAS) ---
    public function getCategoriesProperty()
    {
        return [
            'EPP' => 'Equipos de Protección (EPP)',
            'HERRAMIENTAS' => 'Herramientas Manuales/Poder',
            'FERRETERIA' => 'Ferretería y Sujeción',
            'INSUMOS' => 'Insumos y Consumibles',
            'LUBRICANTES' => 'Aceites y Lubricantes',
            'REPUESTOS' => 'Repuestos de Maquinaria',
            'IZAJE' => 'Elementos de Izaje',
            'VARIOS' => 'Otros Materiales'
        ];
    }

    public function getUnitsProperty()
    {
        return [
            'Unidad' => 'Unidad (Und)',
            'Par' => 'Par (Par)',
            'Juego' => 'Juego/Set (Jgo)',
            'Caja' => 'Caja (Cja)',
            'Paquete' => 'Paquete (Pqt)',
            'Metro' => 'Metro Lineal (m)',
            'Metro2' => 'Metro Cuadrado (m2)',
            'Kg' => 'Kilogramo (Kg)',
            'Litro' => 'Litro (Lt)',
            'Galon' => 'Galón (Gl)',
            'Cilindro' => 'Cilindro/Tambor',
            'Rollo' => 'Rollo (Rll)'
        ];
    }

    // --- SOLICITUD ---
    public function openRequestModal($itemId)
    {
        $this->reset(['requestQty', 'workArea', 'justification']);
        $this->currentItem = WarehouseItem::find($itemId);
        if (!$this->currentItem) return;
        $this->isRequesting = true;
    }
    
    public function submitSingleRequest()
    {
        $this->validate([
            'requestQty' => 'required|numeric|min:1|max:' . ($this->currentItem->stock_actual ?? 1),
            'workArea' => 'required',
            'justification' => 'required|min:5',
        ]);
        
        $tipo = $this->currentItem->item_type ?? 'consumable';
        $returnStatus = ($tipo === 'returnable') ? 'PENDIENTE_RETORNO' : 'N/A';
        
        WarehouseRequest::create([
            'user_id' => Auth::id(),
            'company_id' => Auth::user()->company_id,
            'items' => [[ 
                'id' => $this->currentItem->id,
                'name' => $this->currentItem->nombre,
                'qty' => $this->requestQty,
                'item_type' => $tipo, 
            ]],
            'status' => 'Pendiente',
            'work_area' => $this->workArea,
            'return_status' => $returnStatus,
            'comments' => $this->justification,
        ]);

        $this->reset(['isRequesting', 'currentItem', 'requestQty', 'workArea', 'justification']);
        session()->flash('success_message', 'Solicitud enviada correctamente.');
    }

    // --- GESTIÓN (ADMIN) ---
    public function openModal() { 
        if (!$this->isAdmin) return; 
        // AQUÍ OCURRÍA EL ERROR: Ahora reseteamos las variables correctas
        $this->reset(['item_id', 'internal_code', 'nombre', 'descripcion', 'stock_actual', 'stock_minimo', 'unidad', 'categoria', 'imagen', 'datasheet', 'item_type', 'location']);
        $this->item_type = 'consumable';
        $this->isManaging = true; 
    }

    public function saveItem()
    {
        if (!$this->isAdmin) abort(403);
        $this->validate();
        
        $imagePath = $this->imagen ? $this->imagen->store('warehouse/items', 'public') : null;
        $datasheetPath = $this->datasheet ? $this->datasheet->store('warehouse/docs', 'public') : null;

        $data = [
            'internal_code' => $this->internal_code, // Guardar código
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'stock_actual' => $this->stock_actual,
            'stock_minimo' => $this->stock_minimo,
            'unidad' => $this->unidad,
            'categoria' => $this->categoria,
            'item_type' => $this->item_type,
            'location' => $this->location, // Guardar ubicación
        ];

        if ($imagePath) $data['imagen_path'] = $imagePath;
        if ($datasheetPath) $data['datasheet_path'] = $datasheetPath; // Guardar ficha

        if ($this->item_id) {
            WarehouseItem::find($this->item_id)->update($data);
            session()->flash('message', 'Ítem actualizado.');
        } else {
            if ($imagePath) $data['imagen_path'] = $imagePath;
            WarehouseItem::create($data);
            session()->flash('message', 'Ítem registrado en inventario.');
        }

        $this->isManaging = false;
        // Reseteamos usando los nombres correctos
        $this->reset(['item_id', 'nombre', 'imagen', 'datasheet']);
    }

    public function editItem($id)
    {
        $item = WarehouseItem::find($id);
        $this->item_id = $item->id;
        $this->internal_code = $item->internal_code;
        $this->nombre = $item->nombre;
        $this->descripcion = $item->descripcion;
        $this->stock_actual = $item->stock_actual;
        $this->stock_minimo = $item->stock_minimo;
        $this->unidad = $item->unidad;
        $this->categoria = $item->categoria;
        $this->item_type = $item->item_type ?? 'consumable';
        $this->location = $item->location;
        $this->isManaging = true;
    }

    public function deleteItem($id) { if ($this->isAdmin) WarehouseItem::find($id)->delete(); }

    public function render()
    {
        $query = WarehouseItem::query();
        if($this->search) $query->where('nombre', 'like', '%'.$this->search.'%')
                                ->orWhere('internal_code', 'like', '%'.$this->search.'%');
        if($this->categoryFilter) $query->where('categoria', $this->categoryFilter);

        return view('livewire.warehouse.catalog', ['items' => $query->latest()->get()])
            ->layout('layouts.warehouse');
    }
}