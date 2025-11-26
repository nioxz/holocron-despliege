<div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Gestión de Personal</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 h-fit">
                <h3 class="text-lg font-bold text-indigo-600 mb-4">
                    {{ $isEditing ? 'Editar Colaborador' : 'Registrar Nuevo' }}
                </h3>
                
                @if (session()->has('message'))
                    <div class="bg-green-100 text-green-800 p-2 rounded mb-4 text-sm font-bold border border-green-200">{{ session('message') }}</div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-100 text-red-800 p-2 rounded mb-4 text-sm font-bold border border-red-200">{{ session('error') }}</div>
                @endif

                <form wire:submit.prevent="save" class="space-y-3">
                    <input type="text" wire:model="name" placeholder="Nombres" class="w-full rounded bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    
                    <input type="text" wire:model="surname" placeholder="Apellidos" class="w-full rounded bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm">
                    @error('surname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                    <input type="text" wire:model="dni" placeholder="DNI" class="w-full rounded bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm">
                    @error('dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                    <input type="email" wire:model="email" placeholder="Correo" class="w-full rounded bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                    <input type="text" wire:model="job_title" placeholder="Cargo" class="w-full rounded bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm">
                    @error('job_title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mt-2">Rol en el Sistema:</label>
                    <select wire:model="selected_role" class="w-full rounded bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm mb-2">
                        <option value="worker">👷 Trabajador (Operativo)</option>
                        <option value="supervisor">👮 Supervisor SST (Jefe)</option>
                        <option value="warehouse">📦 Almacenero (Logística)</option>
                    </select>

                    <input type="password" wire:model="password" placeholder="{{ $isEditing ? 'Nueva Contraseña (Opcional)' : 'Contraseña Inicial' }}" class="w-full rounded bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                    <div class="flex gap-2 pt-2">
                        @if($isEditing)
                            <button type="button" wire:click="resetFields" class="w-1/2 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 rounded">Cancelar</button>
                        @endif
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded transition">
                            {{ $isEditing ? 'Guardar Cambios' : '+ Registrar' }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Colaborador</th>
                            <th class="px-6 py-3">Rol Asignado</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($staff as $employee)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900 dark:text-white">{{ $employee->name }} {{ $employee->surname }}</p>
                                <p class="text-xs">{{ $employee->email }}</p>
                                <p class="text-xs text-gray-400">DNI: {{ $employee->dni }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($employee->warehouse_role === 'admin')
                                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-bold">📦 ALMACENERO</span>
                                @elseif($employee->role === 'supervisor')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">👮 SUPERVISOR</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-bold">👷 TRABAJADOR</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 flex gap-2">
                                <button wire:click="edit({{ $employee->id }})" class="text-blue-500 hover:text-blue-700 font-bold text-xs uppercase">Editar</button>
                                <button wire:click="deleteUser({{ $employee->id }})" class="text-red-500 hover:text-red-700 font-bold text-xs uppercase ml-2" onclick="return confirm('¿Eliminar?')">Borrar</button>
                            </td>
                        </tr>
                        @endforeach
                        @if($staff->isEmpty())
                            <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay personal registrado aún.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>