<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('hub') }}" wire:navigate title="Volver al HUB">
                        <img src="{{ asset('img/logo-transparente.png') }}" class="block h-9 w-auto invert" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    
                    <x-nav-link :href="route('warehouse.catalog')" :active="request()->routeIs('warehouse.catalog')" wire:navigate class="text-gray-300 hover:text-orange-400">
                        {{ __('Catálogo') }}
                    </x-nav-link>

                    <x-nav-link :href="route('warehouse.requests')" :active="request()->routeIs('warehouse.requests')" wire:navigate class="text-gray-300 hover:text-orange-400">
                        {{ __('Mis Solicitudes') }}
                    </x-nav-link>

                    @if(auth()->user()->warehouse_role === 'admin' || auth()->user()->role === 'supervisor')
                        <x-nav-link :href="route('warehouse.manage')" :active="request()->routeIs('warehouse.manage')" wire:navigate class="text-orange-500 font-bold hover:text-orange-400">
                            {{ __('Gestión (Admin)') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-800 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('hub')">
                            {{ __('Volver al Inicio') }}
                        </x-dropdown-link>
                        <button wire:click="logout" class="w-full text-left">
                            <x-dropdown-link>
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>