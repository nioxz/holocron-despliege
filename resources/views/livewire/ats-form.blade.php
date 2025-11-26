<div class="py-12 bg-gray-900 min-h-screen text-gray-100">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-center font-bold shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h1 class="text-3xl font-bold text-white">Formulario ATS</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded transition border border-gray-600">← Volver al Panel</a>
        </div>

        <div class="bg-gray-800 overflow-hidden shadow-2xl sm:rounded-lg p-8 border border-gray-700">
            
            @if($currentStep == 1)
            <div class="animate-fade-in">
                <h2 class="text-xl font-bold text-orange-500 mb-6 border-b border-gray-600 pb-2">Paso 1: Datos de la Tarea</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Trabajo a Realizar:</label>
                        <input type="text" wire:model="trabajo" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-orange-500 focus:border-orange-500">
                        @error('trabajo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Lugar / Labor:</label>
                        <input type="text" wire:model="lugar" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-orange-500 focus:border-orange-500">
                        @error('lugar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Fecha:</label>
                        <input type="date" wire:model="fecha" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Supervisor:</label>
                        <input type="text" wire:model="supervisor_responsable" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>

                <div class="mb-8 bg-gray-700/30 p-4 rounded-lg border border-gray-600">
                    <label class="block text-gray-300 font-bold mb-3">Personal Involucrado (Participantes):</label>
                    @foreach($participantes as $index => $participante)
                        <div class="flex gap-2 mb-2">
                            <input type="text" wire:model="participantes.{{ $index }}.dni" placeholder="DNI" class="w-1/3 bg-gray-700 border-gray-600 rounded text-white placeholder-gray-500">
                            <input type="text" wire:model="participantes.{{ $index }}.nombre" placeholder="Nombre Completo" class="w-full bg-gray-700 border-gray-600 rounded text-white placeholder-gray-500">
                            @if($index > 0)
                                <button wire:click="removeParticipante({{ $index }})" class="text-red-500 font-bold px-2 hover:text-red-400">✕</button>
                            @endif
                        </div>
                    @endforeach
                    <button wire:click="addParticipante" class="mt-2 text-blue-400 text-sm hover:text-blue-300 font-bold flex items-center">
                        <span class="text-xl mr-1">+</span> Añadir otro trabajador
                    </button>
                    @error('participantes.0.dni') <span class="text-red-500 text-xs block mt-2 font-bold">Agrega al menos un participante.</span> @enderror
                </div>

                <h3 class="text-lg font-bold text-gray-300 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Equipos de Protección Personal:
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-y-8 gap-x-4 mb-8 justify-items-center">
                    @php
                        $epps = [
                            'Casco' => 'casco.png',
                            'Botas' => 'botas.png',
                            'Lentes' => 'lentes.png',
                            'Guantes' => 'guantes.png',
                            'Arnés' => 'arnes.png',
                            'Orejeras' => 'orejeras.png',
                            'Respirador' => 'respirador.png',
                            'Mameluco' => 'mameluco.png'
                        ];
                    @endphp

                    @foreach($epps as $nombre => $imagen)
                        <div wire:click="toggleEpp('{{ $nombre }}')" class="group cursor-pointer flex flex-col items-center relative w-32">
                            
                            <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center shadow-lg transition-all duration-300 ease-in-out z-10
                                {{ in_array($nombre, $epps_seleccionados) 
                                   ? 'ring-4 ring-orange-500 ring-offset-4 ring-offset-gray-800 scale-110' 
                                   : 'hover:scale-110 hover:shadow-orange-500/50 opacity-90 hover:opacity-100' 
                                }}">
                                <img src="{{ asset('img/' . $imagen) }}" alt="{{ $nombre }}" class="w-16 h-16 object-contain">
                            </div>

                            @if(in_array($nombre, $epps_seleccionados))
                                <div class="absolute top-0 right-2 bg-green-500 text-white rounded-full p-1 shadow-md animate-bounce z-20 border-2 border-gray-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            @endif

                            <span class="mt-3 text-sm font-bold tracking-wide transition-colors duration-200 text-center
                                {{ in_array($nombre, $epps_seleccionados) ? 'text-orange-400' : 'text-gray-400 group-hover:text-white' }}">
                                {{ $nombre }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-bold mb-2">Otros EPPs (Especifique):</label>
                    <input type="text" wire:model="otro_epp" placeholder="Ej: Traje Tyvek, Careta soldar..." class="w-full bg-gray-700 border-gray-600 rounded text-white placeholder-gray-500 focus:ring-orange-500">
                </div>
                @error('epps_seleccionados') <span class="text-red-500 text-xs font-bold">Selecciona al menos un EPP o escribe uno.</span> @enderror

            </div>
            @endif

            @if($currentStep == 2)
            <div class="animate-fade-in">
                <h2 class="text-xl font-bold text-orange-500 mb-6 border-b border-gray-600 pb-2">Paso 2: Análisis Paso a Paso</h2>
                
                <div class="space-y-4">
                    @foreach($pasos as $index => $paso)
                        <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600 relative group hover:bg-gray-700 transition">
                            <span class="absolute -left-3 -top-3 bg-gray-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold border border-gray-500 shadow-sm">
                                {{ $index + 1 }}
                            </span>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-xs text-gray-400 font-bold mb-1 block">PASO DE LA TAREA:</label>
                                    <textarea wire:model="pasos.{{ $index }}.paso" rows="3" class="w-full bg-gray-800 border-gray-600 rounded text-white text-sm focus:ring-orange-500 focus:border-orange-500" placeholder="¿Qué se va a hacer?"></textarea>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 font-bold mb-1 block">PELIGROS:</label>
                                    <textarea wire:model="pasos.{{ $index }}.peligro" rows="3" class="w-full bg-gray-800 border-gray-600 rounded text-white text-sm focus:ring-orange-500 focus:border-orange-500" placeholder="Riesgos asociados"></textarea>
                                </div>
                                <div class="relative">
                                    <label class="text-xs text-gray-400 font-bold mb-1 block">MEDIDAS DE CONTROL:</label>
                                    <textarea wire:model="pasos.{{ $index }}.control" rows="3" class="w-full bg-gray-800 border-gray-600 rounded text-white text-sm focus:ring-orange-500 focus:border-orange-500" placeholder="¿Cómo nos protegemos?"></textarea>
                                    
                                    @if(count($pasos) > 1)
                                        <button wire:click="removePaso({{ $index }})" class="absolute -right-2 -bottom-2 text-red-400 hover:text-red-300 bg-gray-800 rounded-full p-1 shadow hover:bg-gray-600 transition" title="Eliminar paso">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button wire:click="addPaso" class="mt-4 flex items-center text-orange-400 hover:text-orange-300 font-bold text-sm transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Agregar otro paso
                </button>
            </div>
            @endif

            @if($currentStep == 3)
            <div class="animate-fade-in">
                <h2 class="text-xl font-bold text-orange-500 mb-6 border-b border-gray-600 pb-2">Paso 3: Declaración y Evidencia</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="bg-gray-700 p-6 rounded-lg border border-gray-600 h-fit">
                        <h3 class="font-bold text-white mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Compromiso de Seguridad
                        </h3>
                        <p class="text-sm text-gray-300 mb-4 text-justify">
                            Declaro que he inspeccionado el área de trabajo, identificado los peligros y aplicaré las medidas de control establecidas en este ATS. Me comprometo a detener la tarea si las condiciones cambian.
                        </p>
                        <div class="flex items-center bg-gray-800 p-3 rounded border border-gray-600">
                            <input type="checkbox" wire:model="acepto_declaracion" id="declara_ats" class="h-5 w-5 text-orange-600 bg-gray-700 border-gray-500 rounded focus:ring-orange-500 cursor-pointer">
                            <label for="declara_ats" class="ml-3 text-white font-bold cursor-pointer select-none">Sí, acepto y estoy listo.</label>
                        </div>
                        @error('acepto_declaracion') <span class="text-red-500 text-xs block mt-2 font-bold">Debes aceptar para continuar.</span> @enderror
                    </div>

                    <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
                        <h3 class="font-bold text-white mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0010.07 4h3.86a2 2 0 001.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Evidencia (Foto del Lugar/Permiso)
                        </h3>
                        
                        <div class="mb-4">
                            <input type="file" wire:model="foto_evidencia" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-600 file:text-white hover:file:bg-orange-700 cursor-pointer bg-gray-800 rounded border border-gray-600"/>
                            <p class="text-xs text-gray-400 mt-1">Sube una foto panorámica del área o del equipo reunido.</p>
                            @error('foto_evidencia') <span class="text-red-500 text-xs block mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="border-2 border-dashed border-gray-500 rounded-lg h-48 flex items-center justify-center bg-gray-800 overflow-hidden relative">
                            <div wire:loading wire:target="foto_evidencia" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-800 bg-opacity-90 z-10">
                                <svg class="animate-spin h-8 w-8 text-orange-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span class="text-orange-400 text-xs font-bold">Subiendo imagen...</span>
                            </div>
                            @if ($foto_evidencia)
                                <img src="{{ $foto_evidencia->temporaryUrl() }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-gray-500 text-sm">Vista previa aquí</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex justify-between mt-8 pt-4 border-t border-gray-700">
                @if($currentStep > 1)
                    <button wire:click="decreaseStep" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-500 font-bold transition">Anterior</button>
                @else
                    <div></div>
                @endif

                @if($currentStep < 3)
                    <button wire:click="increaseStep" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700 font-bold shadow-lg transition">Siguiente</button>
                @else
                    <button wire:click="submit" class="bg-green-600 text-white px-8 py-2 rounded hover:bg-green-700 font-bold shadow-lg transition transform hover:scale-105 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Guardar ATS
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>