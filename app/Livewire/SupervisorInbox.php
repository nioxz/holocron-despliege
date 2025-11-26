<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Document;
use Illuminate\Support\Facades\Auth; // Para guardar quién fue el supervisor

class SupervisorInbox extends Component
{
    public $selectedDocument = null;
    public $showModal = false;
    public $supervisor_comments = ''; // <--- NUEVA VARIABLE PARA EL COMENTARIO

    public function render()
    {
        $documents = Document::where('status', 'En espera')
                             ->with('user')
                             ->latest()
                             ->get();

        return view('livewire.supervisor-inbox', compact('documents'))
               ->layout('layouts.app');
    }

    public function openReview($id)
    {
        $this->selectedDocument = Document::find($id);
        $this->supervisor_comments = ''; // Limpiamos el comentario anterior
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedDocument = null;
        $this->supervisor_comments = ''; // Limpiamos al cerrar
    }

    public function approve()
    {
        if ($this->selectedDocument) {
            $this->selectedDocument->update([
                'status' => 'Aprobado',
                'supervisor_comments' => $this->supervisor_comments, // Guardamos el comentario
                'supervisor_id' => Auth::id() // Guardamos quién lo aprobó
            ]);
            $this->closeModal();
        }
    }

    public function reject()
    {
        if ($this->selectedDocument) {
            $this->selectedDocument->update([
                'status' => 'Rechazado',
                'supervisor_comments' => $this->supervisor_comments, // Guardamos el comentario
                'supervisor_id' => Auth::id() // Guardamos quién lo rechazó
            ]);
            $this->closeModal();
        }
    }
}