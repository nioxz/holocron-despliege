<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Almacén y Logística') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen flex items-center justify-center">
        <div class="text-center">
            <div class="inline-flex items-center justify-center p-6 bg-orange-900/30 rounded-full mb-6 animate-pulse">
                <svg class="w-16 h-16 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Módulo en Construcción</h1>
            <p class="text-gray-400 max-w-md mx-auto mb-8">
                Estamos preparando la tienda virtual de EPPs y herramientas. Pronto podrás gestionar tu inventario desde aquí.
            </p>
            <a href="{{ route('hub') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-bold transition">
                ← Volver al Hub
            </a>
        </div>
    </div>
</x-app-layout>