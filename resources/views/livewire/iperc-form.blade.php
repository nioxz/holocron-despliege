<div class="py-12 bg-gray-900 min-h-screen text-gray-100">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center font-bold">H</div>
                <h1 class="text-3xl font-bold">Formulario IPERC</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded transition">← Volver a Selección</a>
        </div>

        <div class="bg-gray-800 overflow-hidden shadow-2xl sm:rounded-lg p-8 border border-gray-700">

            @if($currentStep == 1)
                <div class="animate-fade-in">
                    <h2 class="text-xl font-bold mb-6 border-b border-gray-600 pb-2">Paso 1: Datos de la Tarea</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Fecha:</label>
                            <input type="date" wire:model="fecha" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-blue-500 focus:border-blue-500">
                            @error('fecha') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Actividad / Tarea:</label>
                            <input type="text" wire:model="actividad" placeholder="Ej: Mantenimiento de Bomba" class="w-full bg-gray-700 border-gray-600 rounded text-white placeholder-gray-500">
                            @error('actividad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Lugar:</label>
                            <input type="text" wire:model="lugar" placeholder="Ej: Nivel 350 - Cámara de Bombas" class="w-full bg-gray-700 border-gray-600 rounded text-white placeholder-gray-500">
                            @error('lugar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Participantes:</label>
                            @foreach($participantes as $index => $participante)
                                <div class="flex gap-2 mb-2">
                                    <input type="text" wire:model="participantes.{{ $index }}.dni" placeholder="DNI" class="w-1/3 bg-gray-700 border-gray-600 rounded text-white">
                                    <input type="text" wire:model="participantes.{{ $index }}.nombre" placeholder="Nombre Completo" class="w-full bg-gray-700 border-gray-600 rounded text-white">
                                    @if($index > 0)
                                        <button wire:click="removeParticipante({{ $index }})" class="text-red-500 font-bold px-2">X</button>
                                    @endif
                                </div>
                            @endforeach
                            <button wire:click="addParticipante" class="text-blue-400 text-sm hover:underline">+ Añadir Participante</button>
                        </div>
                    </div>
                </div>
            @endif

            @if($currentStep == 2)
                <div class="animate-fade-in">
                    <h2 class="text-xl font-bold mb-6 border-b border-gray-600 pb-2">Paso 2: Peligros, Riesgos y Controles</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Peligro Identificado:</label>
                            <textarea wire:model="peligro" rows="3" class="w-full bg-gray-700 border-gray-600 rounded text-white"></textarea>
                            @error('peligro') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Riesgo Asociado:</label>
                            <textarea wire:model="riesgo" rows="3" class="w-full bg-gray-700 border-gray-600 rounded text-white"></textarea>
                            @error('riesgo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Medidas de Control Propuestas:</label>
                            <textarea wire:model="medidas" rows="4" class="w-full bg-gray-700 border-gray-600 rounded text-white"></textarea>
                            @error('medidas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            @if($currentStep == 3)
                <div class="animate-fade-in">
                    <h2 class="text-xl font-bold mb-6 border-b border-gray-600 pb-2">Paso 3: Evaluación de Riesgo</h2>
                    
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('img/matriz.png') }}" alt="Matriz de Riesgos" class="rounded-lg shadow-md w-full max-w-2xl mx-auto border border-gray-600">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-700 p-6 rounded-lg">
                        <div>
                            <label class="block text-gray-300 font-bold mb-2">Severidad:</label>
                            <select wire:model.live="severidad" class="w-full bg-gray-600 border-gray-500 rounded text-white">
                                <option value="">Seleccione...</option>
                                <option value="1">1: Catastrófico</option>
                                <option value="2">2: Mortalidad</option>
                                <option value="3">3: Permanente</option>
                                <option value="4">4: Temporal</option>
                                <option value="5">5: Menor</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-300 font-bold mb-2">Frecuencia:</label>
                            <select wire:model.live="frecuencia" class="w-full bg-gray-600 border-gray-500 rounded text-white">
                                <option value="">Seleccione...</option>
                                <option value="A">A: Común</option>
                                <option value="B">B: Ha sucedido</option>
                                <option value="C">C: Podría suceder</option>
                                <option value="D">D: Raro que suceda</option>
                                <option value="E">E: Prácticamente imposible</option>
                            </select>
                        </div>
                    </div>

                    @if($nivelRiesgo)
                        <div class="mt-6 p-4 rounded-lg text-center text-white font-bold text-xl {{ $colorRiesgo }} shadow-lg transform transition-all scale-105">
                            NIVEL DE RIESGO: {{ $nivelRiesgo }}
                        </div>
                    @endif

                    <div class="mt-8 bg-gray-700 rounded-lg overflow-hidden border border-gray-600 text-sm">
                        <table class="w-full text-left text-gray-300">
                            <thead class="bg-gray-800 text-gray-100 font-bold">
                                <tr>
                                    <th class="p-3 border-b border-gray-600">NIVEL</th>
                                    <th class="p-3 border-b border-gray-600">DESCRIPCIÓN</th>
                                    <th class="p-3 border-b border-gray-600 text-center">PLAZO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-red-900/50">
                                    <td class="p-3 font-bold text-red-400 border-b border-gray-600">ALTO</td>
                                    <td class="p-3 border-b border-gray-600">Riesgo intolerable. Paralizar labores.</td>
                                    <td class="p-3 text-center border-b border-gray-600 font-bold">0-24 H</td>
                                </tr>
                                <tr class="bg-yellow-900/50">
                                    <td class="p-3 font-bold text-yellow-400 border-b border-gray-600">MEDIO</td>
                                    <td class="p-3 border-b border-gray-600">Iniciar medidas para reducir riesgo.</td>
                                    <td class="p-3 text-center border-b border-gray-600 font-bold">0-72 H</td>
                                </tr>
                                <tr class="bg-green-900/50">
                                    <td class="p-3 font-bold text-green-400">BAJO</td>
                                    <td class="p-3">Riesgo tolerable.</td>
                                    <td class="p-3 text-center font-bold">1 MES</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($currentStep == 4)
                <div class="animate-fade-in">
                    <h2 class="text-xl font-bold mb-6 border-b border-gray-600 pb-2">Paso 4: Validación de Identidad</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="bg-gray-700 p-6 rounded-lg border border-gray-600 h-fit">
                            <h3 class="font-bold text-white mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Declaración Jurada
                            </h3>
                            <p class="text-sm text-gray-300 mb-4 text-justify">
                                Declaro haber participado en la elaboración de este IPERC, haber comprendido los riesgos y controles, y estar en condiciones óptimas para realizar la tarea.
                            </p>
                            <div class="flex items-center bg-gray-800 p-3 rounded border border-gray-600">
                                <input type="checkbox" wire:model="acepto_declaracion" id="declara" class="h-5 w-5 text-blue-600 bg-gray-700 border-gray-500 rounded focus:ring-blue-500 cursor-pointer">
                                <label for="declara" class="ml-3 text-white font-bold cursor-pointer select-none">Sí, acepto y firmo digitalmente.</label>
                            </div>
                            @error('acepto_declaracion') <span class="text-red-500 text-xs block mt-2 font-bold">Debes marcar la casilla.</span> @enderror
                        </div>

                        <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
                            <h3 class="font-bold text-white mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0010.07 4h3.86a2 2 0 001.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Evidencia (Foto Equipo/Lugar)
                            </h3>
                            
                            <div class="mb-4">
                                <input type="file" wire:model="foto_evidencia" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer bg-gray-800 rounded border border-gray-600"/>
                                <p class="text-xs text-gray-400 mt-1">Sube una foto del equipo de trabajo o del área.</p>
                                @error('foto_evidencia') <span class="text-red-500 text-xs block mt-1 font-bold">{{ $message }}</span> @enderror
                            </div>

                            <div class="border-2 border-dashed border-gray-500 rounded-lg h-48 flex items-center justify-center bg-gray-800 overflow-hidden relative">
                                
                                <div wire:loading wire:target="foto_evidencia" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-800 bg-opacity-90 z-10">
                                    <svg class="animate-spin h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span class="text-blue-400 text-xs font-bold">Subiendo imagen...</span>
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
                    <button wire:click="decreaseStep" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-500 font-bold transition">
                        Anterior
                    </button>
                @else
                    <div></div>
                @endif

                @if($currentStep < 4)
                    <button wire:click="increaseStep" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-bold shadow-lg transition">
                        Siguiente
                    </button>
                @else
                    <button wire:click="submit" class="bg-green-600 text-white px-8 py-2 rounded hover:bg-green-700 font-bold shadow-lg transition transform hover:scale-105">
                        Guardar IPERC
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>