<?php

namespace App\Livewire\Setup;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SetupPassword extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    public function save()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($this->password);
        $user->is_password_changed = true; // Marcamos que ya cumplió
        $user->save();

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.setup.setup-password')->layout('layouts.guest');
    }
}