<?php

namespace App\Livewire\AuthNew;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $surname = '';
    public $dni = '';
    public $birthdate = '';
    public $job_title = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $terms = false;

    public function register()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:20'],
            'birthdate' => ['required', 'date'],
            'job_title' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['company_id'] = 1;
        unset($validated['terms']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth_new.register')
            ->layout('layouts.guest');
    }
}
