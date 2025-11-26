<div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="p-2 bg-white dark:bg-gray-800 rounded-full shadow text-gray-500 hover:text-indigo-600 transition" title="Volver al Panel">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white tracking-tight">Cartelera Digital SST</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Comunicados oficiales, alertas y capacitaciones.</p>
                </div>
            </div>
            
            @if(Auth::user()->role === 'supervisor')
                <button wire:click="$toggle('isCreating')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-full shadow-lg transition transform hover:scale-105 flex items-center gap-2">
                    <span class="text-xl">+</span> Nueva Publicación
                </button>
            @endif
        </div>

        @if($isCreating)
        <div class="mb-10 bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-indigo-100 dark:border-gray-700 animate-fade-in-down">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Redactar Comunicado</h3>
            
            <form wire:submit.prevent="create" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Título</label>
                        <input type="text" wire:model="titulo" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500">
                        @error('titulo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                        <select wire:model="tipo" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="Noticia">📰 Noticia General</option>
                            <option value="Alerta">🚨 Alerta de Seguridad</option>
                            <option value="Charla">🗣️ Charla de 5 Min</option>
                            <option value="Reconocimiento">🏆 Reconocimiento</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Contenido</label>
                    <textarea wire:model="contenido" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                    @error('contenido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Imagen</label>
                        <input type="file" wire:model="imagen" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-600 dark:file:text-white"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Archivo Adjunto</label>
                        <input type="file" wire:model="archivo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 dark:file:bg-gray-600 dark:file:text-white"/>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" wire:model.live="add_quiz" id="add_quiz" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label for="add_quiz" class="ml-2 block text-sm font-bold text-indigo-600 dark:text-indigo-400 cursor-pointer">
                            Incluir Evaluación / Examen
                        </label>
                    </div>

                    @if($add_quiz)
                        <div class="space-y-4 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            @foreach($questions as $index => $q)
                                <div class="bg-white dark:bg-gray-800 p-3 rounded border border-gray-200 dark:border-gray-700 relative">
                                    <label class="text-xs font-bold text-gray-500 uppercase">Pregunta {{ $index + 1 }}</label>
                                    <input type="text" wire:model="questions.{{ $index }}.question" placeholder="Escribe la pregunta..." class="w-full mb-2 rounded border-gray-300 dark:bg-gray-700 dark:text-white text-sm">
                                    
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($q['options'] as $optIndex => $option)
                                            <div>
                                                <div class="flex items-center gap-1">
                                                    <input type="radio" name="correct_{{ $index }}" wire:click="$set('questions.{{ $index }}.correct', {{ $optIndex }})" {{ $q['correct'] == $optIndex ? 'checked' : '' }}>
                                                    <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}" placeholder="Opción {{ $optIndex + 1 }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white text-xs">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <button type="button" wire:click="removeQuestion({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-xs font-bold">Eliminar</button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addQuestion" class="text-sm text-indigo-600 hover:underline font-bold">+ Agregar otra pregunta</button>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" wire:click="$set('isCreating', false)" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded-lg text-gray-700 dark:text-white hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-lg">Publicar</button>
                </div>
            </form>
        </div>
        @endif

        <div class="space-y-8">
            @foreach($anuncios as $anuncio)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row hover:shadow-2xl transition duration-300">
                    
                    @if($anuncio->imagen_path)
                        <div class="md:w-1/3 h-48 md:h-auto bg-gray-200 relative">
                            <img src="{{ asset('storage/' . $anuncio->imagen_path) }}" class="absolute inset-0 w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="p-6 flex-1 flex flex-col justify-between {{ $anuncio->tipo === 'Alerta' ? 'border-l-8 border-red-500 bg-red-50 dark:bg-red-900/10' : '' }}">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                    @if($anuncio->tipo == 'Alerta') bg-red-100 text-red-800 border border-red-200
                                    @elseif($anuncio->tipo == 'Charla') bg-green-100 text-green-800 border border-green-200
                                    @else bg-blue-100 text-blue-800 border border-blue-200 @endif">
                                    {{ $anuncio->tipo }}
                                </span>
                                @if(Auth::user()->role === 'supervisor')
                                    <button wire:click="delete({{ $anuncio->id }})" class="text-gray-400 hover:text-red-500 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                @endif
                            </div>

                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ $anuncio->titulo }}</h3>
                            
                            <div class="prose dark:prose-invert text-gray-600 dark:text-gray-300 text-sm mb-4">
                                {!! nl2br(e($anuncio->contenido)) !!}
                            </div>

                            @if($anuncio->quiz_data)
                                <livewire:quiz-widget :announcement="$anuncio" :wire:key="'quiz-'.$anuncio->id" />
                            @endif

                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700 mt-4">
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <span class="font-bold mr-1">{{ $anuncio->user->name }}</span>
                                <span>• {{ $anuncio->created_at->diffForHumans() }}</span>
                            </div>
                            @if($anuncio->archivo_path)
                                <a href="{{ asset('storage/' . $anuncio->archivo_path) }}" target="_blank" class="flex items-center text-indigo-600 hover:text-indigo-800 font-bold text-sm transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Descargar Adjunto
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>