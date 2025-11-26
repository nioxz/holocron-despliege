<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class ChecklistForm extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    
    // Datos Generales
    public $tipo_checklist = '';
    public $fecha, $referencia, $supervisor;
    
    // Matriz Unificada (Nombre, Estado, Comentario)
    public $items = []; 
    
    // Cierre
    public $observaciones_finales = '';
    public $foto_evidencia;
    public $acepto_declaracion = false;

    // --- BANCO DE PREGUNTAS ---
    private $templates = [
        'MAQUINARIA' => [
            'Sistemas de Frenos', 'Sistema de Dirección', 'Alarmas de Retroceso', 
            'Luces', 'Neumáticos / Orugas', 'Fugas de Fluidos', 
            'Cinturones de Seguridad', 'Espejos', 'Radio', 'Extintor'
        ],
        'ALTURA' => [
            'Arnés de cuerpo entero', 'Línea de Vida', 'Punto de Anclaje', 
            'Escaleras', 'Andamios', 'Barandas', 'Delimitación área'
        ],
        'CALIENTE' => [
            'Extintor PQS', 'Biombos / Mantas', 'Materiales inflamables retirados', 
            'Equipo Oxicorte', 'Máquina de Soldar', 'EPP Soldador', 'Vigía de Fuego'
        ],
        'VIAS' => [
            'Señalización', 'Paletero / Vigía', 'Iluminación', 'Superficie vía', 
            'Bermas', 'Riego (Polvo)'
        ],
        'HERRAMIENTAS' => [
            'Cables eléctricos', 'Enchufes', 'Interruptor', 'Guardas', 
            'Discos / Brocas', 'Carcasa', 'Puesta a tierra', 'Cinta del mes'
        ],
        'GENERICO' => [] // Empieza vacío
    ];

    public function mount()
    {
        $this->fecha = date('Y-m-d');
    }

    public function selectType($type)
    {
        $this->tipo_checklist = $type;
        $this->items = [];

        // Si es predefinido, cargamos las preguntas
        if (isset($this->templates[$type])) {
            foreach ($this->templates[$type] as $pregunta) {
                $this->items[] = ['name' => $pregunta, 'status' => '', 'comment' => '', 'is_fixed' => true];
            }
        }
        
        // Si es genérico o si queremos que siempre haya espacio para más,
        // podemos agregar filas vacías, pero mejor dejamos que el botón lo haga.
        if ($type === 'GENERICO') {
            $this->addItem(); // Agregamos una fila vacía para empezar
        }

        $this->currentStep = 2;
    }

    // Cambiar estado (Bien/Mal/NA)
    public function setItemStatus($index, $status)
    {
        $this->items[$index]['status'] = $status;
    }

    // Agregar fila nueva (funciona en TODOS los tipos)
    public function addItem()
    {
        $this->items[] = ['name' => '', 'status' => '', 'comment' => '', 'is_fixed' => false];
    }

    // Eliminar fila (Solo las que no son fijas)
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function increaseStep()
    {
        $this->currentStep++;
    }

    public function decreaseStep()
    {
        $this->currentStep--;
    }

    public function submit()
    {
        $this->validate([
            'referencia' => 'required',
            'foto_evidencia' => 'required|image|max:10240',
            'acepto_declaracion' => 'accepted'
        ]);

        $rutaFoto = $this->foto_evidencia->store('evidencias', 'public');

        // Limpiamos los ítems vacíos antes de guardar
        $final_items = array_filter($this->items, function($i) {
            return !empty($i['name']) && !empty($i['status']);
        });

        Document::create([
            'user_id' => Auth::id(),
            'type' => 'Checklist ' . $this->tipo_checklist,
            'status' => 'En espera',
            'content' => [
                'fecha' => $this->fecha,
                'tipo' => $this->tipo_checklist,
                'referencia' => $this->referencia,
                'supervisor' => $this->supervisor,
                'items' => $final_items, // Guardamos la estructura completa (nombre, estado, comentario)
                'observaciones' => $this->observaciones_finales,
                'foto_evidencia' => $rutaFoto,
                
                // Datos dashboard
                'lugar' => $this->referencia,
                'actividad' => 'Inspección ' . $this->tipo_checklist,
                'peligro' => 'Condición Subestándar'
            ]
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.checklist-form')->layout('layouts.app');
    }
}