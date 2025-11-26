<?php

namespace App\Livewire\Setup;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SetupTerms extends Component
{
    public $accepted = false;

    public function save()
    {
        $this->validate([
            'accepted' => 'accepted'
        ]);

        $user = Auth::user();
        $user->terms_accepted_at = now(); // Guardamos la fecha exacta
        $user->save();

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.setup.setup-terms')->layout('layouts.guest');
    }
}