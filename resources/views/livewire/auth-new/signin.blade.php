<div class="text-white">
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit.prevent="login" class="space-y-5">

        <h2 class="text-3xl font-bold text-center text-white mb-8 tracking-tight">
            Iniciar Sesión
        </h2>

        <div>
            <label for="email" class="block font-medium text-sm text-gray-300 mb-1">
                ID de Usuario (Email)
            </label>

            <input wire:model="email" id="email"
                   class="block w-full bg-gray-700 border-gray-600 rounded-lg text-white shadow-sm
                          focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                   type="email" name="email" required autofocus autocomplete="username"
                   placeholder="ejemplo@holocron.com" />

            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-300 mb-1">
                Contraseña
            </label>

            <input wire:model="password" id="password"
                   class="block w-full bg-gray-700 border-gray-600 rounded-lg text-white shadow-sm
                          focus:ring-blue-500 focus:border-blue-500"
                   type="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="remember" id="remember" type="checkbox"
                       class="rounded bg-gray-700 border-gray-500 text-blue-600 shadow-sm
                              focus:ring-blue-500 hover:bg-gray-600 transition">

                <span class="ms-2 text-sm text-gray-300">Recordarme</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-400 hover:text-white transition"
                   href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif

            <button type="submit"
                    class="ms-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg
                           transition transform hover:scale-105 focus:outline-none focus:ring-2
                           focus:ring-offset-2 focus:ring-blue-500">
                Ingresar
            </button>
        </div>

        <div class="text-center mt-8 pt-6 border-t border-gray-700">
            <a href="{{ route('register') }}"
               class="text-sm font-medium text-blue-400 hover:text-blue-300 hover:underline transition">
                ¿No tienes cuenta? Crear Nuevo Usuario
            </a>
        </div>
    </form>
</div>
