<div class="py-12 bg-gray-900 min-h-screen text-gray-100">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-red-600 flex items-center justify-center font-bold shadow-lg animate-pulse">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h1 class="text-3xl font-bold text-white">PETAR</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded transition border border-gray-600">← Volver</a>
        </div>

        <div class="bg-gray-800 overflow-hidden shadow-2xl sm:rounded-lg p-8 border-2 border-red-900">
            
            @if($currentStep == 1)
                <div class="animate-fade-in">
                    <h2 class="text-xl font-bold text-red-500 mb-6 border-b border-gray-600 pb-2">Paso 1: ¿Qué trabajo de Alto Riesgo realizarás?</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <button wire:click="selectType('ALTURA')" class="group bg-gray-700 p-6 rounded-xl border border-gray-600 hover:border-red-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-3">🏗️</span>
                            <span class="font-bold text-lg group-hover:text-red-400">Trabajo en Altura</span>
                            <span class="text-xs text-gray-400 mt-1">Encima de 1.80m</span>
                        </button>

                        <button wire:click="selectType('CALIENTE')" class="group bg-gray-700 p-6 rounded-xl border border-gray-600 hover:border-red-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-3">🔥</span>
                            <span class="font-bold text-lg group-hover:text-red-400">Trabajo en Caliente</span>
                            <span class="text-xs text-gray-400 mt-1">Soldadura, Esmerilado</span>
                        </button>

                        <button wire:click="selectType('CONFINADO')" class="group bg-gray-700 p-6 rounded-xl border border-gray-600 hover:border-red-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-3">🕳️</span>
                            <span class="font-bold text-lg group-hover:text-red-400">Espacio Confinado</span>
                            <span class="text-xs text-gray-400 mt-1">Tanques, Silos, Zanjas</span>
                        </button>

                        <button wire:click="selectType('IZAJE')" class="group bg-gray-700 p-6 rounded-xl border border-gray-600 hover:border-red-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-3">🏗️</span>
                            <span class="font-bold text-lg group-hover:text-red-400">Izaje de Cargas</span>
                            <span class="text-xs text-gray-400 mt-1">Grúas, Puentes grúa</span>
                        </button>

                        <button wire:click="selectType('ELECTRICO')" class="group bg-gray-700 p-6 rounded-xl border border-gray-600 hover:border-red-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-3">⚡</span>
                            <span class="font-bold text-lg group-hover:text-red-400">Alta Tensión</span>
                            <span class="text-xs text-gray-400 mt-1">Subestaciones, Tableros</span>
                        </button>

                        <button wire:click="selectType('EXCAVACION')" class="group bg-gray-700 p-6 rounded-xl border border-gray-600 hover:border-red-500 hover:bg-gray-600 transition flex flex-col items-center">
                            <span class="text-4xl mb-3">🚜</span>
                            <span class="font-bold text-lg group-hover:text-red-400">Excavaciones</span>
                            <span class="text-xs text-gray-400 mt-1">Zanjas profundas</span>
                        </button>

                    </div>
                </div>
            @endif

            @if($currentStep == 2)
                <div class="animate-fade-in">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-600 pb-2">
                        <h2 class="text-xl font-bold text-red-500">Permiso para: {{ $tipo_trabajo }}</h2>
                        <button wire:click="decreaseStep" class="text-sm text-gray-400 hover:text-white">Cambiar Tipo</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Área / Sección:</label>
                            <input type="text" wire:model="area" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-red-500">
                            @error('area') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Lugar Exacto:</label>
                            <input type="text" wire:model="lugar_exacto" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-bold mb-2">Fecha:</label>
                            <input type="date" wire:model="fecha" class="w-full bg-gray-700 border-gray-600 rounded text-white focus:ring-red-500">
                        </div>
                    </div>

                    <div class="bg-red-900/20 p-4 rounded border border-red-800/50 mb-6 flex gap-4 items-center">
                        <span class="text-red-400 font-bold">⏱️ VIGENCIA:</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-400">Desde:</span>
                            <input type="time" wire:model="hora_inicio" class="bg-gray-800 border-gray-600 rounded text-white text-sm">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-400">Hasta:</span>
                            <input type="time" wire:model="hora_fin" class="bg-gray-800 border-gray-600 rounded text-white text-sm">
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-gray-300 mb-4">Autorizaciones Requeridas (Nombres):</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-400 text-xs uppercase font-bold mb-1">Líder del Trabajo</label>
                            <input type="text" wire:model="responsable_trabajo" placeholder="Ing. Responsable" class="w-full bg-gray-800 border-gray-500 rounded text-white">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs uppercase font-bold mb-1">Supervisor de Área</label>
                            <input type="text" wire:model="supervisor_area" placeholder="Dueño del área" class="w-full bg-gray-800 border-gray-500 rounded text-white">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs uppercase font-bold mb-1">Ing. Seguridad (HSE)</label>
                            <input type="text" wire:model="jefe_seguridad" placeholder="Visto Bueno SSOMA" class="w-full bg-gray-800 border-gray-500 rounded text-white">
                        </div>
                    </div>

                    <div class="mb-4">
                         <label class="block text-gray-300 font-bold mb-2">Personal Autorizado:</label>
                         @foreach($participantes as $index => $persona)
                            <div class="flex gap-2 mb-2">
                                <input type="text" wire:model="participantes.{{ $index }}.nombre" placeholder="Nombre del Técnico" class="w-full bg-gray-700 border-gray-600 rounded text-white">
                                <button wire:click="removeParticipante({{ $index }})" class="text-red-500 font-bold px-2">✕</button>
                            </div>
                         @endforeach
                         <button wire:click="addParticipante" class="text-red-400 text-sm hover:text-red-300 font-bold">+ Agregar Técnico</button>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button wire:click="increaseStep" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 font-bold shadow-lg transition">
                            Continuar
                        </button>
                    </div>
                </div>
            @endif

            @if($currentStep == 3)
                <div class="animate-fade-in">
                    <h2 class="text-xl font-bold text-red-500 mb-6 border-b border-gray-600 pb-2">Paso 3: Validación Final</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
                            <h3 class="font-bold text-white mb-2">Declaración</h3>
                            <p class="text-sm text-gray-300 mb-4 text-justify">
                                Declaro que se han verificado todas las condiciones de seguridad, se cuenta con el IPERC y ATS firmado, y se tienen los equipos de rescate disponibles en caso de emergencia.
                            </p>
                            <div class="flex items-center bg-gray-800 p-3 rounded border border-gray-600">
                                <input type="checkbox" wire:model="acepto_declaracion" class="h-5 w-5 text-red-600 bg-gray-700 border-gray-500 rounded focus:ring-red-500 cursor-pointer">
                                <label class="ml-3 text-white font-bold">Entendido y aceptado.</label>
                            </div>
                            @error('acepto_declaracion') <span class="text-red-500 text-xs block mt-2 font-bold">Requerido.</span> @enderror
                        </div>

                        <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
                            <label class="block text-gray-300 font-bold mb-2">Foto del Permiso Físico Firmado:</label>
                            <input type="file" wire:model="foto_evidencia" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer bg-gray-800 rounded border border-gray-600"/>
                            
                            <div class="mt-4 border-2 border-dashed border-gray-500 rounded h-32 flex items-center justify-center overflow-hidden">
                                @if ($foto_evidencia)
                                    <img src="{{ $foto_evidencia->temporaryUrl() }}" class="h-full w-full object-cover">
                                @else
                                    <span class="text-gray-500 text-xs">Subir foto del papel firmado</span>
                                @endif
                            </div>
                             @error('foto_evidencia') <span class="text-red-500 text-xs block mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-between mt-8 pt-4 border-t border-gray-700">
                        <button wire:click="decreaseStep" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-500 font-bold transition">Anterior</button>
                        <button wire:click="submit" class="bg-red-600 text-white px-8 py-2 rounded hover:bg-red-700 font-bold shadow-lg transition transform hover:scale-105 animate-pulse">
                            EMITIR PETAR
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>