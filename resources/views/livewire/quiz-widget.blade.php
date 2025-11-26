<div class="mt-4 bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800 transition-all">
    
    @if(!$finished)
        <h4 class="font-bold text-indigo-700 dark:text-indigo-300 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Evaluación de Conocimiento
        </h4>

        <div class="space-y-4">
            @foreach($announcement->quiz_data as $index => $q)
                <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <p class="font-bold text-gray-800 dark:text-white text-sm mb-2">{{ $index + 1 }}. {{ $q['question'] }}</p>
                    <div class="space-y-2">
                        @foreach($q['options'] as $optIndex => $option)
                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-1 rounded transition">
                                <input type="radio" wire:model="answers.{{ $index }}" value="{{ $optIndex }}" class="text-indigo-600 focus:ring-indigo-500 border-gray-300 bg-gray-100 dark:bg-gray-900 dark:border-gray-600">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <button wire:click="submitQuiz" class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition shadow flex justify-center items-center gap-2">
            <span>Enviar Respuestas</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
        </button>

    @else
        <div class="text-center py-4 border-b border-gray-200 dark:border-gray-700 mb-4">
            @if($score >= 70)
                <div class="text-green-500 mb-2 animate-bounce">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">¡Aprobado!</h3>
                <p class="text-gray-500 dark:text-gray-400">Tu nota: <span class="text-green-600 font-bold">{{ $score }}/100</span></p>
            @else
                <div class="text-red-500 mb-2">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Inténtalo de nuevo</h3>
                <p class="text-gray-500 dark:text-gray-400">Tu nota: <span class="text-red-600 font-bold">{{ $score }}/100</span></p>
            @endif
        </div>
    @endif

    @if(Auth::user()->role === 'supervisor')
        <div class="mt-4 pt-2 border-t border-gray-200 dark:border-gray-700">
            <button wire:click="toggleResults" class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1 w-full justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                {{ $showResults ? 'Ocultar Resultados' : '📊 Ver Resultados del Personal' }}
            </button>

            @if($showResults)
                <div class="mt-4 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden animate-fade-in">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Trabajador</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nota</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($attemptsList as $attempt)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $attempt->user->name }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        @if($attempt->passed)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ $attempt->score }} / 100
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                {{ $attempt->score }} / 100
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $attempt->created_at->format('d/m H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Aún nadie ha tomado este examen.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
</div>