<div class="py-12 bg-gray-900 min-h-screen text-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-orange-600/20 rounded-lg border border-orange-500/30">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Catálogo de Almacén</h1>
                    <p class="text-sm text-gray-400">Gestión de stock, EPPs y herramientas.</p>
                </div>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('warehouse.requests') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg font-bold text-sm border border-gray-700 transition">
                    Mis Solicitudes
                </a>
                <a href="{{ route('hub') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg text-sm font-bold border border-gray-700 transition">
                    Salir al Hub
                </a>

                @if($this->isAdmin)
                    <button wire:click="openModal" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg flex items-center gap-2 transition border border-green-500">
                        + Registrar Ítem
                    </button>
                @endif
            </div>
        </div>

        @if (session()->has('success_message'))
            <div class="bg-green-900/50 border-l-4 border-green-500 text-green-100 p-4 mb-6 rounded shadow-sm">{{ session('success_message') }}</div>
        @endif
        @if (session()->has('message'))
            <div class="bg-blue-900/50 border-l-4 border-blue-500 text-blue-100 p-4 mb-6 rounded shadow-sm">{{ session('message') }}</div>
        @endif

        <div class="bg-gray-800 p-4 rounded-xl shadow-md border border-gray-700 mb-8 flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <svg class="w-5 h-5 absolute left-3 top-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input wire:model.live="search" type="text" placeholder="Buscar por nombre o código interno..." class="w-full pl-10 rounded-lg border-gray-600 bg-gray-900 text-white placeholder-gray-500 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <select wire:model.live="categoryFilter" class="w-full md:w-64 rounded-lg border-gray-600 bg-gray-900 text-white focus:ring-orange-500">
                <option value="">Todas las Categorías</option>
                @foreach($this->categories as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 pb-24">
            @forelse($items as $item)
                <div class="group bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 hover:border-orange-500 transition-all duration-300">
                    
                    <div class="h-40 bg-gray-700 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase shadow-sm {{ $item->item_type === 'returnable' ? 'bg-blue-600 text-white' : 'bg-red-600 text-white' }}">
                                {{ $item->item_type === 'returnable' ? 'Retornable' : 'Consumible' }}
                            </span>
                        </div>
                        @if($item->imagen_path)
                            <img src="{{ asset('storage/' . $item->imagen_path) }}" class="w-full h-full object-cover group-hover:opacity-80 transition">
                        @else
                            <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        @endif
                        
                        <div class="absolute top-2 right-2 px-2 py-1 rounded text-xs font-bold {{ $item->stock_actual <= $item->stock_minimo ? 'bg-red-600 animate-pulse' : 'bg-green-600' }}">
                            {{ $item->stock_actual }} {{ $item->unidad }}
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="flex justify-between">
                            <div class="text-[10px] font-bold text-gray-400 uppercase mb-1">{{ $item->internal_code ?? 'S/C' }}</div>
                            <div class="text-[10px] font-bold text-orange-400 uppercase mb-1">{{ $item->categoria }}</div>
                        </div>
                        <h3 class="text-md font-bold text-white truncate mb-1" title="{{ $item->nombre }}">{{ $item->nombre }}</h3>
                        
                        @if($item->location)
                            <div class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $item->location }}
                            </div>
                        @endif

                        <div class="mt-2 flex gap-2">
                            <button wire:click="openRequestModal({{ $item->id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-bold text-xs uppercase tracking-wide transition shadow-lg">
                                Solicitar
                            </button>
                            @if($this->isAdmin)
                                <button wire:click="editItem({{ $item->id }})" class="px-3 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg border border-gray-600">✎</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500"><p>No hay ítems registrados.</p></div>
            @endforelse
        </div>

        @if($isRequesting && $currentItem)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
                <div class="bg-gray-800 p-8 rounded-xl shadow-2xl w-full max-w-lg border border-gray-700">
                    <h3 class="text-xl font-bold text-white mb-4 border-b border-gray-700 pb-2">Solicitud: {{ $currentItem->nombre }}</h3>
                    <form wire:submit.prevent="submitSingleRequest" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Cantidad</label><input type="number" wire:model="requestQty" min="1" max="{{ $currentItem->stock_actual }}" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white">@error('requestQty') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                            <div><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Stock</label><input type="text" value="{{ $currentItem->stock_actual }} {{ $currentItem->unidad }}" disabled class="w-full bg-gray-700 border-gray-600 rounded-lg text-gray-400"></div>
                        </div>
                        <div><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Área / Frente de Trabajo</label><input type="text" wire:model="workArea" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white">@error('workArea') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                        <div><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Justificación</label><textarea wire:model="justification" rows="2" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white"></textarea>@error('justification') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                        <div class="text-xs text-gray-500 pt-2 border-t border-gray-700 flex justify-between"><span>Tipo de Control:</span><span class="font-bold text-white uppercase {{ ($currentItem->item_type ?? '') === 'returnable' ? 'text-blue-400' : 'text-red-400' }}">{{ ($currentItem->item_type ?? 'consumable') === 'returnable' ? 'Retornable (Préstamo)' : 'Consumible (Gasto)' }}</span></div>
                        <div class="flex justify-end gap-3 pt-4"><button type="button" wire:click="$set('isRequesting', false)" class="px-4 py-2 bg-gray-600 text-white rounded-lg">Cancelar</button><button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700">Enviar Solicitud</button></div>
                    </form>
                </div>
            </div>
        @endif

        @if($isManaging)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 overflow-y-auto">
                <div class="bg-gray-800 p-6 rounded-xl shadow-2xl w-full max-w-2xl border border-gray-700 my-8">
                    <h3 class="text-xl font-bold text-white mb-4">{{ $item_id ? 'Editar Producto' : 'Nuevo Producto' }}</h3>
                    <form wire:submit.prevent="saveItem" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-400 uppercase">Nombre del Producto</label><input type="text" wire:model="nombre" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white">@error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                         <div><label class="block text-xs font-bold text-gray-400 uppercase">Código Interno / SKU</label><input type="text" wire:model="internal_code" placeholder="Ej: EPP-001" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white"></div>
                         <div><label class="block text-xs font-bold text-gray-400 uppercase">Ubicación Física</label><input type="text" wire:model="location" placeholder="Ej: Rack A-02" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white"></div>
                         <div><label class="block text-xs font-bold text-gray-400 uppercase">Stock Actual</label><input type="number" wire:model="stock_actual" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white">@error('stock_actual') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                         <div><label class="block text-xs font-bold text-gray-400 uppercase">Stock Mínimo (Alerta)</label><input type="number" wire:model="stock_minimo" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white"></div>
                         <div><label class="block text-xs font-bold text-gray-400 uppercase">Unidad</label><select wire:model="unidad" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white">@foreach($this->units as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</select></div>
                         <div><label class="block text-xs font-bold text-gray-400 uppercase">Categoría</label><select wire:model="categoria" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white">@foreach($this->categories as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</select></div>
                         <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-400 uppercase">Tipo de Trazabilidad</label><div class="flex gap-4 mt-1"><label class="flex items-center cursor-pointer"><input type="radio" wire:model="item_type" value="consumable" class="text-green-500 bg-gray-900 border-gray-600"><span class="ml-2 text-white text-sm">Consumible (Se gasta)</span></label><label class="flex items-center cursor-pointer"><input type="radio" wire:model="item_type" value="returnable" class="text-blue-500 bg-gray-900 border-gray-600"><span class="ml-2 text-white text-sm">Retornable (Se devuelve)</span></label></div></div>
                         <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-400 uppercase">Foto</label><input type="file" wire:model="imagen" class="block w-full text-sm text-gray-500 file:bg-gray-700 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-2"></div>
                         <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-400 uppercase">Ficha Técnica (PDF/Img)</label><input type="file" wire:model="datasheet" class="block w-full text-sm text-gray-500 file:bg-gray-700 file:text-white file:border-0 file:rounded-lg file:px-4 file:py-2"></div>
                        <div class="md:col-span-2 flex justify-end gap-3 pt-4 border-t border-gray-700 mt-2"><button type="button" wire:click="$set('isManaging', false)" class="px-4 py-2 bg-gray-600 text-white rounded-lg font-bold">Cancelar</button><button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg font-bold shadow-lg">Guardar Datos</button></div>
                    </form>
                </div>
            </div>
        @endif

    </div>
</div>