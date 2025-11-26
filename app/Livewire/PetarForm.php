<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Document;
use App\Models\User; // <--- Importante para buscar supervisores
use App\Notifications\PetarCreated; // <--- Importante para la notificación
use Illuminate\Support\Facades\Auth;

class PetarForm extends Component
{
    use WithFileUploads;

    public $currentStep = 1;

    // Datos Generales
    public $area, $lugar_exacto, $fecha, $hora_inicio, $hora_fin;
    public $tipo_trabajo = ''; 
    
    // Responsables
    public $responsable_trabajo, $supervisor_area, $jefe_seguridad;
    
    // Cuadrilla
    public $participantes = [];

    // Cierre
    public $acepto_declaracion = false;
    public $foto_evidencia;

    public function mount()
    {
        $this->fecha = date('Y-m-d');
        $this->hora_inicio = date('H:i');
        // Iniciamos con 1 participante vacío
        $this->participantes[] = ['nombre' => '', 'firma' => false];
    }

    public function selectType($type)
    {
        $this->tipo_trabajo = $type;
        $this->currentStep = 2;
    }

    public function addParticipante()
    {
        $this->participantes[] = ['nombre' => '', 'firma' => false];
    }

    public function removeParticipante($index)
    {
        unset($this->participantes[$index]);
        $this->participantes = array_values($this->participantes);
    }

    public function increaseStep()
    {
        if ($this->currentStep == 2) {
            $this->validate([
                'area' => 'required',
                'hora_inicio' => 'required',
                'hora_fin' => 'required',
                'responsable_trabajo' => 'required',
                'supervisor_area' => 'required'
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
            'foto_evidencia' => 'required|image|max:10240'
        ]);

        $rutaFoto = $this->foto_evidencia->store('evidencias', 'public');

        // 1. Guardamos el documento y lo capturamos en la variable $documento
        $documento = Document::create([
            'user_id' => Auth::id(),
            'type' => 'PETAR',
            'status' => 'En espera',
            'content' => [
                'tipo_trabajo' => $this->tipo_trabajo,
                'area' => $this->area,
                'lugar' => $this->lugar_exacto,
                'fecha' => $this->fecha,
                'horario' => $this->hora_inicio . ' a ' . $this->hora_fin,
                'autorizaciones' => [
                    'responsable' => $this->responsable_trabajo,
                    'supervisor' => $this->supervisor_area,
                    'hse' => $this->jefe_seguridad
                ],
                'participantes' => $this->participantes,
                'foto_evidencia' => $rutaFoto,
                
                // Datos resumen para dashboard
                'actividad' => 'PETAR: ' . $this->tipo_trabajo,
                'peligro' => 'Alto Riesgo Controlado'
            ]
        ]);

        // 2. ENVIAR NOTIFICACIÓN A SUPERVISORES (AQUÍ ESTÁ LA MAGIA 🔔)
        // Buscamos a todos los usuarios con rol 'supervisor'
        $supervisores = User::where('role', 'supervisor')->get();
        
        // Les enviamos la alerta a cada uno
        foreach ($supervisores as $sup) {
            $sup->notify(new PetarCreated($documento));
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.petar-form')->layout('layouts.app');
    }
}