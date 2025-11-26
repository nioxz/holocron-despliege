<div class="py-12 bg-gray-900 min-h-screen text-gray-100">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center font-bold shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h1 class="text-3xl font-bold text-white">Checklist Universal</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded transition border border-gray-600">← Volver</a>
        </div>

        <div class="bg-gray-800 overflow-hidden shadow-2xl sm:rounded-lg p-8 border border-gray-700">
            
            @if($currentStep == 1)
                <div class="animate-fade-in">
                    <h2 class="text-xl font-bold text-green-500 mb-6 border-b border-gray-600 pb-2">Paso 1: ¿Qué vas a inspeccionar?</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <button wire:click="selectType('MAQUINARIA')" class="group bg-gray-700 p-6 rounded-xl border-2 border-gray-600 hover:border-green-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-2">🚜</span>
                            <span class="font-bold text-lg group-hover:text-green-400">Maquinaria / Vehículo</span>
                            <span class="text-xs text-gray-400 mt-1">Camionetas, Línea Amarilla</span>
                        </button>
                        <button wire:click="selectType('ALTURA')" class="group bg-gray-700 p-6 rounded-xl border-2 border-gray-600 hover:border-blue-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-2">🏗️</span>
                            <span class="font-bold text-lg group-hover:text-blue-400">Trabajos en Altura</span>
                            <span class="text-xs text-gray-400 mt-1">Arnés, Andamios, Escaleras</span>
                        </button>
                        <button wire:click="selectType('CALIENTE')" class="group bg-gray-700 p-6 rounded-xl border-2 border-gray-600 hover:border-red-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-2">🔥</span>
                            <span class="font-bold text-lg group-hover:text-red-400">Trabajos en Caliente</span>
                            <span class="text-xs text-gray-400 mt-1">Soldadura, Oxicorte</span>
                        </button>
                        <button wire:click="selectType('VIAS')" class="group bg-gray-700 p-6 rounded-xl border-2 border-gray-600 hover:border-yellow-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-2">🛣️</span>
                            <span class="font-bold text-lg group-hover:text-yellow-400">Mantenimiento Vías</span>
                            <span class="text-xs text-gray-400 mt-1">Señalización, Bermas</span>
                        </button>
                         <button wire:click="selectType('HERRAMIENTAS')" class="group bg-gray-700 p-6 rounded-xl border-2 border-gray-600 hover:border-purple-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-2">⚡</span>
                            <span class="font-bold text-lg group-hover:text-purple-400">Herramientas Poder</span>
                            <span class="text-xs text-gray-400 mt-1">Taladros, Amoladoras</span>
                        </button>
                        <button wire:click="selectType('GENERICO')" class="group bg-gray-700 p-6 rounded-xl border-2 border-gray-600 hover:border-white hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-2">🛠️</span>
                            <span class="font-bold text-lg group-hover:text-white">Genérico / Otros</span>
                            <span class="text-xs text-gray-400 mt-1">Escribir ítems manualmente</span>
                        </button>
                    </div>
                </div>
            @endif

            @if($currentStep == 2)
                <div class="animate-fade-in">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-600 pb-2">
                        <h2 class="text-xl font-bold text-green-500">Inspección: {{ $tipo_checklist }}</h2>
                        <button wire:click="decreaseStep" class="text-sm text-gray-400 hover:text-white">Cambiar Tipo</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Referencia (Código / Lugar):</label>
                            <input type="text" wire:model="referencia" placeholder="Ej: Taladro Percutor #05" class="w-full bg-gray-700 border-gray-600 rounded text-white">
                            @error('referencia') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Supervisor:</label>
                            <input type="text" wire:model="supervisor" class="w-full bg-gray-700 border-gray-600 rounded text-white">
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($items as $index => $item)
                            <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600 hover:bg-gray-700 transition">
                                
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
                                    
                                    <div class="flex-1 flex items-center gap-2">
                                        <span class="text-gray-500 font-bold text-sm">{{ $index + 1 }}.</span>
                                        @if($item['is_fixed'])
                                            <span class="text-gray-200 font-medium text-lg">{{ $item['name'] }}</span>
                                        @else
                                            <input type="text" wire:model="items.{{ $index }}.name" placeholder="Nombre del ítem..." class="w-full bg-gray-800 border-gray-600 rounded text-white text-sm">
                                        @endif
                                    </div>

                                    <div class="flex space-x-2 shrink-0">
                                        <button wire:click="setItemStatus({{ $index }}, 'Bien')" class="px-3 py-2 rounded font-bold text-xs transition {{ $item['status'] === 'Bien' ? 'bg-green-600 text-white ring-2 ring-green-400' : 'bg-gray-800 text-gray-500 hover:bg-gray-600' }}">✓ Bien</button>
                                        <button wire:click="setItemStatus({{ $index }}, 'Mal')" class="px-3 py-2 rounded font-bold text-xs transition {{ $item['status'] === 'Mal' ? 'bg-red-600 text-white ring-2 ring-red-400' : 'bg-gray-800 text-gray-500 hover:bg-gray-600' }}">X Mal</button>
                                        <button wire:click="setItemStatus({{ $index }}, 'N/A')" class="px-3 py-2 rounded font-bold text-xs transition {{ $item['status'] === 'N/A' ? 'bg-gray-500 text-white' : 'bg-gray-800 text-gray-500 hover:bg-gray-600' }}">N/A</button>
                                        
                                        @if(!$item['is_fixed'])
                                            <button wire:click="removeItem({{ $index }})" class="text-red-400 hover:text-red-300 font-bold px-2">✕</button>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <input type="text" 
                                           wire:model="items.{{ $index }}.comment" 
                                           placeholder="{{ $item['status'] === 'Mal' ? '⚠️ DETALLE OBLIGATORIO: ¿Cuál es la falla?' : 'Observaciones (Opcional)' }}" 
                                           class="w-full text-sm rounded border-0 bg-gray-800 text-gray-300 placeholder-gray-500 focus:ring-1 {{ $item['status'] === 'Mal' ? 'ring-red-500 placeholder-red-400' : 'focus:ring-green-500' }}">
                                </div>
                            </div>
                        @endforeach
                        
                        <button wire:click="addItem" class="mt-4 w-full border-2 border-dashed border-gray-600 rounded-lg p-3 text-gray-400 hover:text-white hover:border-gray-400 hover:bg-gray-700 transition flex items-center justify-center gap-2">
                            <span class="text-xl font-bold">+</span> Agregar otro punto de inspección
                        </button>

                    </div>

                    <div class="mt-8 flex justify-end">
                        <button wire:click="increaseStep" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold shadow-lg transition">
                            Siguiente
                        </button>
                    </div>
                </div>
            @endif

            @if($currentStep == 3)
                <div class="animate-fade-in">
                    <h2 class="text-xl font-bold text-green-500 mb-6 border-b border-gray-600 pb-2">Paso 3: Finalizar Inspección</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
                            <label class="block text-gray-300 font-bold mb-2">Comentarios Finales:</label>
                            <textarea wire:model="observaciones_finales" rows="4" class="w-full bg-gray-800 border-gray-600 rounded text-white" placeholder="Conclusiones generales..."></textarea>
                            <div class="mt-4 flex items-center bg-gray-800 p-3 rounded border border-gray-600">
                                <input type="checkbox" wire:model="acepto_declaracion" class="h-5 w-5 text-green-600 bg-gray-700 border-gray-500 rounded focus:ring-green-500 cursor-pointer">
                                <label class="ml-3 text-white font-bold cursor-pointer">Declaro que la inspección es veraz.</label>
                            </div>
                            @error('acepto_declaracion') <span class="text-red-500 text-xs block mt-2 font-bold">Requerido.</span> @enderror
                        </div>
                        <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
                            <label class="block text-gray-300 font-bold mb-2">Evidencia Fotográfica (Obligatorio):</label>
                            <div class="mb-4">
                                <input type="file" wire:model="foto_evidencia" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700 cursor-pointer bg-gray-800 rounded border border-gray-600"/>
                                @error('foto_evidencia') <span class="text-red-500 text-xs block mt-1 font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div class="border-2 border-dashed border-gray-500 rounded-lg h-40 flex items-center justify-center bg-gray-800 overflow-hidden relative">
                                <div wire:loading wire:target="foto_evidencia" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-800 bg-opacity-90 z-10"><span class="text-green-400 text-xs font-bold">Cargando...</span></div>
                                @if ($foto_evidencia) <img src="{{ $foto_evidencia->temporaryUrl() }}" class="h-full w-full object-cover"> @else <span class="text-gray-500 text-sm">Vista previa</span> @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8 pt-4 border-t border-gray-700">
                        <button wire:click="decreaseStep" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-500 font-bold transition">Anterior</button>
                        <button wire:click="submit" class="bg-green-600 text-white px-8 py-2 rounded hover:bg-green-700 font-bold shadow-lg transition transform hover:scale-105">Registrar Checklist</button>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>