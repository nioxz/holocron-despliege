<div class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Historial de Documentos</h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">← Volver al Panel</a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($documents as $doc)
                            <tbody x-data="{ open: false }" class="border-b hover:bg-gray-50 transition">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">#{{ $doc->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $doc->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $doc->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($doc->status == 'Aprobado')
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-800">APROBADO</span>
                                        @elseif($doc->status == 'Rechazado')
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800">RECHAZADO</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-800">EN ESPERA</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="open = !open" class="text-indigo-600 hover:text-indigo-900 cursor-pointer font-bold mr-3">
                                            <span x-text="open ? 'Ocultar Detalles' : 'Ver Detalles'"></span>
                                        </button>
                                    </td>
                                </tr>
                                
                                <tr x-show="open" x-transition class="bg-gray-50">
                                    <td colspan="5" class="px-6 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <h4 class="font-bold text-gray-700 mb-2">Datos del Documento:</h4>
                                                <ul class="text-sm text-gray-600 space-y-1">
                                                    <li><strong>Actividad:</strong> {{ $doc->content['actividad'] ?? '-' }}</li>
                                                    <li><strong>Lugar:</strong> {{ $doc->content['lugar'] ?? '-' }}</li>
                                                    <li><strong>Peligro:</strong> {{ $doc->content['peligro'] ?? '-' }}</li>
                                                    <li><strong>Medidas:</strong> {{ $doc->content['medidas'] ?? '-' }}</li>
                                                </ul>
                                            </div>

                                            <div class="border-l pl-4 border-gray-300">
                                                <h4 class="font-bold text-gray-700 mb-2">Estado de Revisión:</h4>
                                                
                                                @if($doc->supervisor_id)
                                                    <p class="text-sm text-gray-600">Revisado por: <strong>{{ $doc->supervisor->name ?? 'Supervisor' }}</strong></p>
                                                    <div class="mt-2 p-3 rounded {{ $doc->status == 'Aprobado' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                                        <p class="text-xs font-bold uppercase text-gray-500 mb-1">Comentarios del Supervisor:</p>
                                                        <p class="text-gray-800 italic">"{{ $doc->supervisor_comments ?? 'Sin comentarios adicionales.' }}"</p>
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-500 italic">Este documento aún no ha sido revisado.</p>
                                                @endif

                                                <div class="mt-4">
                                                    <a href="{{ route('document.print', $doc->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                        Imprimir Documento
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>