<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Mis Solicitudes</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Seguimiento de tus pedidos y devoluciones.</p>
            </div>
            
            <a href="{{ route('warehouse.catalog') }}" class="bg-gray-800 dark:bg-gray-700 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-bold text-sm transition border border-gray-600">
                ← Volver al Catálogo
            </a>
        </div>

        @if (session()->has('message'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded shadow-sm animate-fade-in-down">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
            
            @if($requests->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <p>No has realizado ninguna solicitud.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Ítems</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Estado Actual</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase w-1/3">Notas / Instrucciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($requests as $request)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    <div class="font-bold">{{ $request->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $request->created_at->format('H:i') }}</div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    <ul class="list-disc list-inside">
                                        @if(is_array($request->items))
                                            @foreach($request->items as $item)
                                                <li>
                                                    <span class="font-bold text-orange-500">{{ $item['qty'] }}x</span> 
                                                    {{ $item['name'] }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($request->status === 'Pendiente')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            🟡 En Revisión
                                        </span>
                                    
                                    @elseif($request->status === 'POR_RECOGER')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200 animate-pulse">
                                            ✅ APROBADO: RECOGER
                                        </span>
                                    
                                    @elseif($request->status === 'EN_PRESTAMO')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                            ⏳ En tu poder
                                        </span>
                                    @elseif($request->status === 'EN_DEVOLUCION')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                            🔄 Devolviendo...
                                        </span>
                                    @elseif($request->status === 'Rechazado' || $request->status === 'RECHAZADO')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                            ❌ Rechazado
                                        </span>
                                    @elseif(in_array($request->status, ['Entregado', 'DEVUELTO', 'FINALIZADO']))
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            ✅ Finalizado
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">
                                            {{ $request->status }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    
                                    @if($request->comments)
                                        <div class="text-xs p-3 rounded border mb-2 
                                            {{ $request->status === 'POR_RECOGER' ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 text-blue-700' : 'bg-gray-50 dark:bg-gray-700 border-gray-200 text-gray-600 dark:text-gray-300' }}">
                                            <span class="font-bold block mb-1">Nota del Almacén:</span>
                                            "{{ $request->comments }}"
                                        </div>
                                    @endif

                                    @if($request->status === 'EN_PRESTAMO')
                                        <button wire:click="startReturn({{ $request->id }})" class="w-full bg-white hover:bg-gray-50 text-indigo-600 font-bold py-2 px-4 border border-indigo-200 rounded text-xs shadow-sm transition flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            Notificar Devolución
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>