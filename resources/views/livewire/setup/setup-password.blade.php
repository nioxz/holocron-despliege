<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900 text-white">
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-gray-800 shadow-2xl overflow-hidden sm:rounded-lg border border-gray-700">
        
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-blue-400">Seguridad Primero 🔐</h2>
            <p class="text-sm text-gray-400 mt-2">Es tu primer ingreso. Por seguridad, debes cambiar tu contraseña temporal.</p>
        </div>

        <form wire:submit.prevent="save" class="space-y-4">
            
            <div>
                <label class="block text-sm font-bold text-gray-300">Contraseña Actual (DNI)</label>
                <input type="password" wire:model="current_password" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-blue-500">
                @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300">Nueva Contraseña</label>
                <input type="password" wire:model="password" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-blue-500">
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300">Confirmar Nueva Contraseña</label>
                <input type="password" wire:model="password_confirmation" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                Actualizar y Continuar
            </button>
        </form>
    </div>
</div>