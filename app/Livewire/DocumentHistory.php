<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class DocumentHistory extends Component
{
    public function render()
    {
        // Si es supervisor, ve TODO. Si es trabajador, ve SOLO LO SUYO.
        if (Auth::user()->role === 'supervisor') {
            $documents = Document::with(['user', 'supervisor'])->latest()->get();
        } else {
            $documents = Document::where('user_id', Auth::id())
                                 ->with(['supervisor']) // Cargamos los datos del jefe para ver quién rechazó
                                 ->latest()
                                 ->get();
        }

        return view('livewire.document-history', compact('documents'))
               ->layout('layouts.app');
    }
}