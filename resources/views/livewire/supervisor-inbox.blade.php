<div class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-indigo-900">Bandeja de Entrada</h2>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600">← Volver al Panel</a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                
                @if($documents->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <p>🎉 ¡Todo limpio! No hay documentos pendientes de revisión.</p>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trabajador</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($documents as $doc)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">#{{ $doc->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doc->user->name ?? 'Desconocido' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $doc->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $doc->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="openReview({{ $doc->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold cursor-pointer">Revisar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>

    @if($showModal && $selectedDocument)
       <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Revisando Documento #{{ $selectedDocument->id }}
                                </h3>
                                
                                <div class="mt-4 bg-gray-50 p-4 rounded text-sm text-gray-600 space-y-2 text-left mb-4">
                                    <p><strong>Actividad:</strong> {{ $selectedDocument->content['actividad'] ?? '-' }}</p>
                                    <p><strong>Lugar:</strong> {{ $selectedDocument->content['lugar'] ?? '-' }}</p>
                                    <hr class="my-2">
                                    <p><strong>Peligro:</strong> {{ $selectedDocument->content['peligro'] ?? '-' }}</p>
                                    <p><strong>Riesgo:</strong> {{ $selectedDocument->content['riesgo'] ?? '-' }}</p>
                                    <p><strong>Medidas:</strong> {{ $selectedDocument->content['medidas'] ?? '-' }}</p>
                                </div>

                                <div>
                                    <label for="comments" class="block text-sm font-medium text-gray-700">Comentarios / Observaciones:</label>
                                    <textarea 
                                        wire:model="supervisor_comments" 
                                        id="comments" 
                                        rows="3" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2"
                                        placeholder="Escribe aquí la razón del rechazo o aprobación..."></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="approve" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Aprobar ✅
                        </button>
                        <button wire:click="reject" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Rechazar ❌
                        </button>
                        <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>