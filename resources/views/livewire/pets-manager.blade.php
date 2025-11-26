<div class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Biblioteca de PETS (Procedimientos)</h2>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600">← Volver al Panel</a>
        </div>

        @if(Auth::user()->role === 'supervisor')
        <div class="bg-white p-6 rounded-lg shadow-md mb-8 border-t-4 border-blue-600">
            <h3 class="font-bold text-lg mb-4">Subir Nuevo Procedimiento (PDF)</h3>
            <form wire:submit.prevent="save" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full">
                    <label class="block text-sm font-bold text-gray-700">Título del PETS</label>
                    <input type="text" wire:model="titulo" placeholder="Ej: PETS-001 Cambio de Neumáticos" class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('titulo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block text-sm font-bold text-gray-700">Archivo PDF</label>
                    <input type="file" wire:model="archivo_pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                    @error('archivo_pdf') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md font-bold hover:bg-blue-700 h-10">
                    Subir
                </button>
            </form>
            
            @if (session()->has('message'))
                <div class="mt-4 p-2 bg-green-100 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif
        </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <input type="text" wire:model.live="search" placeholder="Buscar procedimiento..." class="w-full border-gray-300 rounded-md mb-4 shadow-sm">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($pets as $pet)
                        <div class="border rounded-lg p-4 hover:shadow-md transition flex items-start gap-4 bg-gray-50">
                            <div class="bg-red-100 text-red-600 p-3 rounded-lg">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <h4 class="font-bold text-gray-800 truncate" title="{{ $pet->titulo }}">{{ $pet->titulo }}</h4>
                                <p class="text-xs text-gray-500 mb-2">Subido por: {{ $pet->user->name }} <br> {{ $pet->created_at->format('d/m/Y') }}</p>
                                
                                <div class="flex gap-2">
                                    <a href="{{ asset('storage/' . $pet->archivo_path) }}" target="_blank" class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">Ver PDF</a>
                                    <a href="{{ asset('storage/' . $pet->archivo_path) }}" download class="text-xs bg-gray-600 text-white px-2 py-1 rounded hover:bg-gray-700">Descargar</a>
                                    
                                    @if(Auth::user()->role === 'supervisor')
                                        <button wire:click="delete({{ $pet->id }})" class="text-xs text-red-500 hover:text-red-700 underline ml-auto">Borrar</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($pets->isEmpty())
                    <p class="text-center text-gray-500 mt-4">No se encontraron procedimientos.</p>
                @endif

            </div>
        </div>
    </div>
</div>