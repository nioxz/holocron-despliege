<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementsBoard extends Component
{
    use WithFileUploads;

    public $titulo, $contenido, $tipo = 'Noticia';
    public $imagen, $archivo;
    public $isCreating = false;

    // VARIABLES PARA EXAMEN
    public $add_quiz = false; // Checkbox para activar examen
    public $questions = [];   // Array de preguntas

    public function mount()
    {
        // Inicializar con una pregunta vacía por si acaso
        $this->addQuestion();
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'question' => '',
            'options' => ['', '', ''], // 3 opciones por defecto
            'correct' => 0 // Índice de la correcta (0, 1 o 2)
        ];
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function create()
    {
        $this->validate([
            'titulo' => 'required|min:5',
            'contenido' => 'required',
            'tipo' => 'required',
            'imagen' => 'nullable|image|max:5120',
            'archivo' => 'nullable|mimes:pdf,docx,xlsx|max:10240',
        ]);

        $imgRoute = $this->imagen ? $this->imagen->store('news_images', 'public') : null;
        $fileRoute = $this->archivo ? $this->archivo->store('news_files', 'public') : null;

        Announcement::create([
            'user_id' => Auth::id(),
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'tipo' => $this->tipo,
            'imagen_path' => $imgRoute,
            'archivo_path' => $fileRoute,
            // Si activó el quiz, guardamos las preguntas, si no, NULL
            'quiz_data' => $this->add_quiz ? $this->questions : null 
        ]);

        $this->reset(['titulo', 'contenido', 'tipo', 'imagen', 'archivo', 'isCreating', 'add_quiz']);
        $this->questions = [];
        $this->addQuestion(); // Resetear preguntas
    }

    public function delete($id)
    {
        if(Auth::user()->role === 'supervisor') {
            Announcement::find($id)->delete();
        }
    }

    public function render()
    {
        return view('livewire.announcements-board', [
            'anuncios' => Announcement::with('user')->latest()->get()
        ])->layout('layouts.app');
    }
}