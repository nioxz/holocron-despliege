<div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Gestión de Despachos</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Bandeja de entrada para procesar solicitudes.</p>
            </div>
            
            <div class="flex flex-col md:flex-row gap-3 items-end">
                
                <div class="flex gap-2 items-center bg-white dark:bg-gray-800 p-1.5 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <span class="text-xs text-gray-500 font-bold px-1">Reporte:</span>
                    <input type="date" wire:model="dateFrom" class="border-0 bg-gray-50 dark:bg-gray-900 rounded text-xs text-gray-600 dark:text-gray-300 focus:ring-0 py-1 px-2">
                    <span class="text-gray-400">-</span>
                    <input type="date" wire:model="dateTo" class="border-0 bg-gray-50 dark:bg-gray-900 rounded text-xs text-gray-600 dark:text-gray-300 focus:ring-0 py-1 px-2">
                    
                    <button wire:click="downloadReport" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-xs font-bold transition shadow flex items-center gap-1" title="Descargar Excel">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Kardex
                    </button>
                </div>
    
                <a href="{{ route('warehouse.catalog') }}" class="px-4 py-2 bg-gray-800 dark:bg-gray-700 hover:bg-gray-700 text-white rounded-lg font-bold text-sm transition border border-gray-700 h-full flex items-center">
                    ← Ir al Catálogo
                </a>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-100 dark:bg-green-900/50 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-6 rounded shadow-sm animate-fade-in-down">{{ session('success') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 dark:bg-red-900/50 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-6 rounded shadow-sm animate-fade-in-down">{{ session('error') }}</div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha / Solicitante</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ítems Requeridos</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trazabilidad</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($requests as $request)
                        @php
                            $firstItem = $request->items[0] ?? ['item_type' => 'consumable'];
                            $itemType = $firstItem['item_type'] ?? 'consumable';
                        @endphp
                        
                        <tr class="{{ $request->status === 'Pendiente' ? 'bg-yellow-50/50 dark:bg-yellow-900/20' : 'bg-white dark:bg-gray-800' }} hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="text-xs text-orange-600 dark:text-orange-400 font-mono mb-1">
                                    {{ $request->created_at->format('d/m H:i') }} <span class="text-gray-400">({{ $request->created_at->diffForHumans() }})</span>
                                </div>
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $request->user->name }}</div>
                                <div class="text-xs text-gray-500">Área: {{ $request->work_area ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <ul class="text-sm text-gray-700 dark:text-gray-300 list-disc list-inside space-y-0.5">
                                    @if(is_array($request->items))
                                        @foreach($request->items as $item)
                                            <li><b class="text-orange-600 dark:text-orange-400">{{ $item['qty'] }}x</b> {{ $item['name'] ?? 'Ítem' }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-[10px] font-bold uppercase rounded {{ $itemType === 'returnable' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    {{ $itemType === 'returnable' ? '🔄 Retornable' : '🗑️ Consumible' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($request->status === 'Pendiente')
                                    <span class="badge bg-yellow-400 text-black animate-pulse">PENDIENTE</span>
                                @elseif($request->status === 'POR_RECOGER')
                                    <span class="badge bg-blue-500 text-white">📍 POR RECOGER</span>
                                @elseif($request->status === 'EN_PRESTAMO')
                                    <span class="badge bg-purple-600 text-white">⏳ EN PRÉSTAMO</span>
                                @elseif($request->status === 'EN_DEVOLUCION')
                                    <span class="badge bg-orange-500 text-white animate-pulse">🔄 EN DEVOLUCIÓN</span>
                                @elseif($request->status === 'FINALIZADO' || $request->status === 'DEVUELTO')
                                    <span class="badge bg-green-600 text-white">✅ FINALIZADO</span>
                                @else
                                    <span class="badge bg-red-600 text-white">❌ RECHAZADO</span>
                                @endif

                                @if($request->comments)
                                    <div class="mt-1 text-[10px] text-gray-500 dark:text-gray-400 italic border-l-2 border-gray-400 pl-1">"{{ Str::limit($request->comments, 20) }}"</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($request->status === 'Pendiente')
                                    <div class="flex flex-col gap-2">
                                        <button wire:click="openActionModal({{ $request->id }}, 'approve')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-bold w-full">
                                            ✓ Aprobar
                                        </button>
                                        <button wire:click="openActionModal({{ $request->id }}, 'reject')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-bold w-full">
                                            ✕ Rechazar
                                        </button>
                                    </div>
                                @elseif($request->status === 'POR_RECOGER')
                                    <button wire:click="confirmDelivery({{ $request->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs font-bold w-full shadow-md">
                                        📦 Confirmar Entrega
                                    </button>
                                @elseif(in_array($request->status, ['EN_PRESTAMO', 'EN_DEVOLUCION']))
                                    <button wire:click="markAsReturned({{ $request->id }})" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-xs font-bold w-full shadow-md">
                                        🔄 Recibir Devolución
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 uppercase">Procesado por {{ $request->processor->name ?? 'Admin' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay pedidos pendientes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($showActionModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div class="bg-gray-800 p-6 rounded-xl w-full max-w-md border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4">{{ $actionType === 'approve' ? 'Confirmar Aprobación' : 'Rechazar Pedido' }}</h3>
                <p class="text-sm text-gray-400 mb-2">{{ $actionType === 'approve' ? 'Instrucciones de recojo:' : 'Motivo del rechazo:' }}</p>
                <textarea wire:model="comment" rows="3" class="w-full bg-gray-900 border-gray-600 rounded-lg text-white mb-4" placeholder="Escribe aquí..."></textarea>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showActionModal', false)" class="px-4 py-2 bg-gray-600 text-white rounded">Cancelar</button>
                    <button wire:click="processRequest" class="px-6 py-2 rounded font-bold text-white {{ $actionType === 'approve' ? 'bg-green-600' : 'bg-red-600' }}">Confirmar</button>
                </div>
            </div>
        </div>
        @endif

    </div>
    <style> .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; } </style>
</div>