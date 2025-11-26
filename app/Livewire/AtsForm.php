<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // Para subir fotos
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class AtsForm extends Component
{
    use WithFileUploads;

    public $currentStep = 1;

    // Paso 1: Datos Generales y Personas
    public $fecha, $trabajo, $lugar, $supervisor_responsable;
    public $participantes = []; // Array para la cuadrilla
    
    // EPPs
    public $epps_seleccionados = [];
    public $otro_epp = '';

    // Paso 2: Matriz de Análisis
    public $pasos = [
        ['paso' => '', 'peligro' => '', 'control' => '']
    ];

    // Paso 3: Cierre
    public $acepto_declaracion = false;
    public $foto_evidencia;

    public function mount()
    {
        $this->fecha = date('Y-m-d');
        // Inicializamos con un participante vacío
        $this->participantes[] = ['dni' => '', 'nombre' => ''];
    }

    // --- GESTIÓN DE PARTICIPANTES ---
    public function addParticipante()
    {
        $this->participantes[] = ['dni' => '', 'nombre' => ''];
    }

    public function removeParticipante($index)
    {
        unset($this->participantes[$index]);
        $this->participantes = array_values($this->participantes);
    }

    // --- GESTIÓN DE EPPs ---
    public function toggleEpp($eppName)
    {
        if (in_array($eppName, $this->epps_seleccionados)) {
            $this->epps_seleccionados = array_diff($this->epps_seleccionados, [$eppName]);
        } else {
            $this->epps_seleccionados[] = $eppName;
        }
    }

    // --- GESTIÓN DE PASOS ATS ---
    public function addPaso()
    {
        $this->pasos[] = ['paso' => '', 'peligro' => '', 'control' => ''];
    }

    public function removePaso($index)
    {
        unset($this->pasos[$index]);
        $this->pasos = array_values($this->pasos);
    }

    // --- NAVEGACIÓN Y VALIDACIÓN ---
    public function increaseStep()
    {
        if ($this->currentStep == 1) {
            $this->validate([
                'fecha' => 'required',
                'trabajo' => 'required',
                'lugar' => 'required',
                'participantes.0.dni' => 'required', // Al menos el primer participante
                // Validar que haya EPPs seleccionados O que se haya escrito en "otro_epp"
                'epps_seleccionados' => 'required_without:otro_epp', 
            ]);
        }
        if ($this->currentStep == 2) {
            $this->validate([
                'pasos.0.paso' => 'required' // Al menos un paso descrito
            ]);
        }
        $this->currentStep++;
    }

    public function decreaseStep()
    {
        $this->currentStep--;
    }

    public function submit()
    {
        $this->validate([
            'acepto_declaracion' => 'accepted',
            'foto_evidencia' => 'required|image|max:10240' // Max 10MB
        ]);

        // Guardar foto
        $rutaFoto = $this->foto_evidencia->store('evidencias', 'public');

        // Unir EPPs seleccionados con el texto "otro"
        $lista_epps = $this->epps_seleccionados;
        if (!empty($this->otro_epp)) {
            $lista_epps[] = $this->otro_epp;
        }

        Document::create([
            'user_id' => Auth::id(),
            'type' => 'ATS',
            'status' => 'En espera',
            // Enviamos el array directo (sin json_encode)
            'content' => [
                'fecha' => $this->fecha,
                'trabajo' => $this->trabajo,
                'lugar' => $this->lugar,
                'supervisor' => $this->supervisor_responsable,
                'participantes' => $this->participantes,
                'epps' => $lista_epps,
                'pasos' => $this->pasos,
                'foto_evidencia' => $rutaFoto,
                // Datos resumen para el dashboard
                'actividad' => $this->trabajo,
                'peligro' => 'Múltiples (Ver ATS)'
            ]
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.ats-form')->layout('layouts.app');
    }
}