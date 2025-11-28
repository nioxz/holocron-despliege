<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    // Definimos las variables para el formulario
    public string $name = '';
    public string $surname = ''; // Nuevo
    public string $dni = '';     // Nuevo
    public string $birthdate = ''; // Nuevo
    public string $job_title = ''; // Nuevo
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $terms = false; // Nuevo (Checkbox)

    public function register(): void
    {
        // 1. Validación de todos los campos
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:20'],
            'birthdate' => ['required', 'date'],
            'job_title' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'], // Obligatorio aceptar
        ]);

        // 2. Encriptar contraseña
        $validated['password'] = Hash::make($validated['password']);
        
        // 3. Asignar empresa por defecto (Holocron ID 1)
        $validated['company_id'] = 1; 

        // 4. Eliminar 'terms' porque no es una columna de la base de datos
        unset($validated['terms']);

        // 5. Crear Usuario
        event(new Registered($user = User::create($validated)));

        // 6. Loguear y Redirigir
        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="text-white">
    <form wire:submit="register" class="space-y-6">
        
        <h2 class="text-2xl font-bold text-center text-white mb-8">Crear Nuevo Usuario</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="space-y-4">
                <div>
                    <label class="block font-medium text-sm text-gray-300 mb-1">Nombres</label>
                    <input wire:model="name" type="text" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white focus:ring-blue-500 focus:border-blue-500" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-300 mb-1">Apellidos</label>
                    <input wire:model="surname" type="text" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white focus:ring-blue-500 focus:border-blue-500" required />
                    <x-input-error :messages="$errors->get('surname')" class="mt-1" />
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-300 mb-1">DNI / Identificación</label>
                    <input wire:model="dni" type="text" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white focus:ring-blue-500 focus:border-blue-500" required />
                    <x-input-error :messages="$errors->get('dni')" class="mt-1" />
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-300 mb-1">Fecha de Nacimiento</label>
                    <input wire:model="birthdate" type="date" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white focus:ring-blue-500 focus:border-blue-500" required />
                    <x-input-error :messages="$errors->get('birthdate')" class="mt-1" />
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block font-medium text-sm text-gray-300 mb-1">Cargo que Ocupa</label>
                    <input wire:model="job_title" type="text" placeholder="Ej: Operador de Maquinaria" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white focus:ring-blue-500 focus:border-blue-500" required />
                    <x-input-error :messages="$errors->get('job_title')" class="mt-1" />
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-300 mb-1">Email Corporativo</label>
                    <input wire:model="email" type="email" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white focus:ring-blue-500 focus:border-blue-500" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-300 mb-1">Contraseña</label>
                    <input wire:model="password" type="password" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white focus:ring-blue-500 focus:border-blue-500" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-300 mb-1">Confirmar Contraseña</label>
                    <input wire:model="password_confirmation" type="password" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white focus:ring-blue-500 focus:border-blue-500" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-700 pt-4">
            <details class="bg-gray-900 p-4 rounded-lg border border-gray-600 shadow-inner">
                <summary class="font-bold text-blue-400 cursor-pointer hover:text-blue-300 select-none flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Leer Acuerdo de Usuario y Responsabilidad (Clic aquí)
                </summary>
                
                <div class="mt-4 text-sm text-gray-300 max-h-60 overflow-y-auto pr-4 text-justify space-y-4 leading-relaxed border-l-2 border-blue-500 pl-4 font-light">
                    <p class="font-bold text-white">LEA ESTE ACUERDO CUIDADOSAMENTE. AL CREAR UNA CUENTA, USTED ACEPTA LOS TÉRMINOS.</p>
                    <p>1. <strong>Objeto:</strong> Regula el uso de la plataforma HOLOCRON.</p>
                    <p>2. <strong>Datos:</strong> Sus datos se usarán para trazabilidad.</p>
                    <p>3. <strong>Responsabilidad:</strong> Su usuario es su firma digital.</p>
                </div>
            </details>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between pt-6 border-t border-gray-700">
            <label class="inline-flex items-start cursor-pointer mb-4 md:mb-0 max-w-md">
                <input wire:model="terms" type="checkbox" class="mt-1 w-5 h-5 rounded bg-gray-700 border-gray-500 text-blue-600 shadow-sm focus:ring-blue-500 transition">
                <span class="ms-3 text-sm text-gray-300 leading-snug">He leído y acepto el Acuerdo de Usuario.</span>
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-1" />

            <div class="flex items-center gap-4">
                <a class="underline text-sm text-gray-400 hover:text-white transition" href="{{ route('login') }}" wire:navigate>
                    ¿Ya tienes cuenta?
                </a>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105">
                    Crear Cuenta
                </button>
            </div>
        </div>
    </form>
</div>