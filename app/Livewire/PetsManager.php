<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // Necesario para subir archivos
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class PetsManager extends Component
{
    use WithFileUploads; // Activar subida de archivos

    public $titulo;
    public $archivo_pdf; // Variable temporal para el archivo
    public $search = '';

    public function save()
    {
        $this->validate([
            'titulo' => 'required|min:3',
            'archivo_pdf' => 'required|mimes:pdf|max:10240', // Solo PDF, máx 10MB
        ]);

        // Guardar archivo en la carpeta 'public/pets'
        $ruta = $this->archivo_pdf->store('pets', 'public');

        Pet::create([
            'titulo' => $this->titulo,
            'archivo_path' => $ruta,
            'user_id' => Auth::id(),
        ]);

        // Limpiar formulario
        $this->titulo = '';
        $this->archivo_pdf = null;
        session()->flash('message', 'PETS subido correctamente.');
    }

    public function delete($id)
    {
        // Solo el supervisor puede borrar (opcional)
        if(Auth::user()->role === 'supervisor'){
            Pet::find($id)->delete();
        }
    }

    public function render()
    {
        $pets = Pet::where('titulo', 'like', '%'.$this->search.'%')
                   ->latest()
                   ->get();

        return view('livewire.pets-manager', compact('pets'))->layout('layouts.app');
    }
}