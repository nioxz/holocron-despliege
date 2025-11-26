<div class="py-12 bg-gray-900 min-h-screen text-white">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-indigo-400">Panel Super Admin (Dueño)</h2>
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white">← Volver</a>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-600 text-white p-4 rounded-lg mb-6 shadow-lg">{{ session('message') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 h-fit">
                <h3 class="text-xl font-bold text-white mb-4 border-b border-gray-700 pb-2">
                    {{ $isEditing ? 'Editar Empresa' : 'Registrar Nuevo Cliente' }}
                </h3>
                
                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'submit' }}">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400">Nombre Comercial</label>
                            <input type="text" wire:model="company_name" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white">
                            @error('company_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400">RUC</label>
                            <input type="text" wire:model="ruc" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white">
                            @error('ruc') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        @if(!$isEditing)
                        <div class="pt-4 border-t border-gray-700">
                            <h4 class="text-green-400 font-bold text-xs uppercase mb-3">Primer Administrador</h4>
                            <input type="text" wire:model="admin_name" placeholder="Nombre Encargado" class="w-full mb-2 bg-gray-900 border-gray-600 rounded-lg text-white text-sm">
                            <input type="email" wire:model="admin_email" placeholder="Correo Admin" class="w-full mb-2 bg-gray-900 border-gray-600 rounded-lg text-white text-sm">
                            <input type="password" wire:model="admin_password" placeholder="Contraseña" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white text-sm">
                        </div>
                        @endif
                    </div>

                    <div class="mt-6 flex gap-2">
                        @if($isEditing)
                            <button type="button" wire:click="resetFields" class="w-1/2 bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 rounded-lg">Cancelar</button>
                        @endif
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg">
                            {{ $isEditing ? 'Actualizar' : 'Crear Cliente' }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2 bg-gray-800 rounded-xl shadow-lg border border-gray-700 overflow-hidden">
                <table class="w-full text-left text-gray-400">
                    <thead class="bg-gray-700 text-gray-200 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Empresa</th>
                            <th class="px-6 py-3">RUC</th>
                            <th class="px-6 py-3">Usuarios</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($companies as $company)
                        <tr class="hover:bg-gray-700/50">
                            <td class="px-6 py-4 font-bold text-white">{{ $company->name }}</td>
                            <td class="px-6 py-4">{{ $company->ruc }}</td>
                            <td class="px-6 py-4"><span class="bg-green-900 text-green-300 text-xs font-bold px-2 py-1 rounded-full">{{ $company->users_count }}</span></td>
                            <td class="px-6 py-4 flex gap-2">
                                <button wire:click="edit({{ $company->id }})" class="text-blue-400 hover:text-blue-300 font-bold text-sm">Editar</button>
                                <button wire:click="delete({{ $company->id }})" class="text-red-400 hover:text-red-300 font-bold text-sm ml-2" onclick="return confirm('¿Borrar empresa y todos sus datos?')">Eliminar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>