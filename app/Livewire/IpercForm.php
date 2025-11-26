<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // <--- IMPORTANTE: Habilita la subida
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class IpercForm extends Component
{
    use WithFileUploads; // <--- IMPORTANTE: Usar el trait

    public $currentStep = 1;

    // Paso 1: Datos
    public $fecha, $actividad, $lugar;
    public $participantes = []; 
    
    // Paso 2: Peligros
    public $peligro, $riesgo, $medidas;

    // Paso 3: Evaluación
    public $severidad = '';
    public $frecuencia = '';
    public $nivelRiesgo = '';
    public $colorRiesgo = '';

    // Paso 4: Declaración y Foto
    public $acepto_declaracion = false;
    public $foto_evidencia; // <--- NUEVA VARIABLE PARA LA FOTO

    public function mount()
    {
        $this->participantes[] = ['dni' => '', 'nombre' => ''];
    }

    public function addParticipante()
    {
        $this->participantes[] = ['dni' => '', 'nombre' => ''];
    }

    public function removeParticipante($index)
    {
        unset($this->participantes[$index]);
        $this->participantes = array_values($this->participantes);
    }

    public function calcularRiesgo()
    {
        if ($this->severidad && $this->frecuencia) {
            $valores = [
                '1' => ['A'=>1,  'B'=>2,  'C'=>4,  'D'=>7,  'E'=>11],
                '2' => ['A'=>3,  'B'=>5,  'C'=>8,  'D'=>12, 'E'=>16],
                '3' => ['A'=>6,  'B'=>9,  'C'=>13, 'D'=>17, 'E'=>20],
                '4' => ['A'=>10, 'B'=>14, 'C'=>18, 'D'=>21, 'E'=>23],
                '5' => ['A'=>15, 'B'=>19, 'C'=>22, 'D'=>24, 'E'=>25],
            ];

            $valor = $valores[$this->severidad][$this->frecuencia] ?? 0;

            if ($valor >= 1 && $valor <= 8) {
                $this->nivelRiesgo = "ALTO ($valor)";
                $this->colorRiesgo = 'bg-red-600';
            } elseif ($valor >= 9 && $valor <= 15) {
                $this->nivelRiesgo = "MEDIO ($valor)";
                $this->colorRiesgo = 'bg-yellow-500 text-black';
            } else {
                $this->nivelRiesgo = "BAJO ($valor)";
                $this->colorRiesgo = 'bg-green-600';
            }
        }
    }

    public function updatedSeveridad() { $this->calcularRiesgo(); }
    public function updatedFrecuencia() { $this->calcularRiesgo(); }

    public function increaseStep()
    {
        if ($this->currentStep == 1) {
            $this->validate(['fecha' => 'required', 'actividad' => 'required', 'lugar' => 'required']);
        }
        if ($this->currentStep == 2) {
            $this->validate(['peligro' => 'required', 'riesgo' => 'required', 'medidas' => 'required']);
        }
        if ($this->currentStep == 3) {
            $this->validate(['severidad' => 'required', 'frecuencia' => 'required']);
        }
        $this->currentStep++;
    }

    public function decreaseStep()
    {
        $this->currentStep--;
    }

    public function submit()
    {
        // Validamos que acepten y que suban foto
        $this->validate([
            'acepto_declaracion' => 'accepted',
            'foto_evidencia' => 'required|image|max:10240' // Max 10MB
        ]);

        // Guardar la foto en el disco 'public' carpeta 'evidencias'
        $rutaFoto = $this->foto_evidencia->store('evidencias', 'public');

        Document::create([
            'user_id' => Auth::id(),
            'type' => 'IPERC',
            'status' => 'En espera',
            'content' => [
                'fecha' => $this->fecha,
                'actividad' => $this->actividad,
                'lugar' => $this->lugar,
                'participantes' => $this->participantes,
                'peligro' => $this->peligro,
                'riesgo' => $this->riesgo,
                'medidas' => $this->medidas,
                'foto_evidencia' => $rutaFoto, // <--- Guardamos la ruta de la foto
                'evaluacion' => [
                    'severidad' => $this->severidad,
                    'frecuencia' => $this->frecuencia,
                    'nivel' => $this->nivelRiesgo
                ]
            ]
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.iperc-form')->layout('layouts.app');
    }
}