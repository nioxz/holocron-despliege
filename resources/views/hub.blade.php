<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hub - Holocron</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">

    <nav x-data="{ 
        openUserMenu: false,
        openNotif: false,
        darkMode: localStorage.getItem('color-theme') === 'dark',
        toggleTheme() {
            this.darkMode = !this.darkMode;
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
        }
    }" class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 relative z-50 shadow-sm">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <div class="flex items-center">
                    <img src="{{ asset('img/logo-transparente.png') }}" alt="Logo" class="h-10 w-auto invert dark:invert-0 transition-all duration-300">
                </div>

                <div class="flex items-center gap-4">
                    
                    <button @click="toggleTheme()" class="p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 hover:text-yellow-500 transition focus:outline-none">
                        <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    @if(auth()->user()->role === 'supervisor')
                    <div class="relative">
                        <button @click="openNotif = !openNotif" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 focus:outline-none relative">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-red-600 ring-2 ring-white animate-pulse"></span>
                            @endif
                        </button>
                        
                        <div x-show="openNotif" @click.away="openNotif = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-2xl py-2 z-50 border border-gray-100 dark:border-gray-700" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700"><span class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Notificaciones</span></div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ route('supervisor.inbox') }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-50 dark:border-gray-700">
                                        <p class="text-sm text-gray-800 dark:text-white font-semibold">{{ $notification->data['message'] }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-sm text-gray-500 text-center">No tienes alertas nuevas.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="relative">
                        <button @click="openUserMenu = !openUserMenu" class="flex items-center gap-2 text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-indigo-500 transition focus:outline-none bg-white dark:bg-gray-800 py-2 px-4 rounded-full shadow-sm border border-gray-200 dark:border-gray-700">
                            <span>Hola, {{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="openUserMenu" @click.away="openUserMenu = false" class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl py-2 z-50 border border-gray-100 dark:border-gray-700" style="display: none;">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <p class="text-xs text-gray-400 uppercase font-bold">Conectado como</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-white truncate">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">Mi Perfil</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-[calc(100vh-4rem)] flex flex-col items-center justify-center py-12">
        
        <div class="text-center mb-12 px-4 animate-fade-in-down">
            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 dark:text-white mb-4 tracking-tight">
                Bienvenido a <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">HOLOCRON</span>
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-lg max-w-2xl mx-auto">
                Selecciona el módulo operativo al que deseas ingresar.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl w-full px-6">
            
            <a href="{{ route('dashboard') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-10 border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/20 flex flex-col items-center text-center overflow-hidden cursor-pointer">
                <div class="relative z-10 bg-blue-100 dark:bg-blue-900/30 p-6 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                    <svg class="w-16 h-16 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h2 class="relative z-10 text-3xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Seguridad (SST)</h2>
                <p class="relative z-10 text-gray-500 dark:text-gray-400 text-sm mb-6">Gestión de IPERC, ATS, Permisos y Reportes.</p>
                <span class="relative z-10 mt-8 px-6 py-2 bg-blue-600 text-white rounded-full font-bold text-sm uppercase tracking-widest group-hover:bg-blue-700 transition shadow-lg flex items-center">INGRESAR <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></span>
            </a>

            <a href="{{ route('warehouse.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-10 border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 transition-all duration-300 hover:shadow-2xl hover:shadow-orange-500/20 flex flex-col items-center text-center overflow-hidden cursor-pointer">
                <div class="relative z-10 bg-orange-100 dark:bg-orange-900/30 p-6 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                    <svg class="w-16 h-16 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h2 class="relative z-10 text-3xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Almacén</h2>
                <p class="relative z-10 text-gray-500 dark:text-gray-400 text-sm mb-6">Solicitud de EPPs y control de inventario.</p>
                <span class="relative z-10 mt-8 px-6 py-2 bg-orange-600 text-white rounded-full font-bold text-sm uppercase tracking-widest group-hover:bg-orange-700 transition shadow-lg flex items-center">INGRESAR <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></span>
            </a>

        </div>

        <div class="mt-16 text-gray-400 text-xs text-center">
            &copy; {{ date('Y') }} Holocron Systems. v2.0
        </div>
    </div>

</body>
</html>