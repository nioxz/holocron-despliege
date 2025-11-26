<nav x-data="{ 
    open: false,
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
}" class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 transition-colors duration-300 sticky top-0 z-50 shadow-sm">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('hub') }}" wire:navigate>
                        <img src="{{ asset('img/logo-transparente.png') }}" alt="Logo" class="h-9 w-auto invert dark:invert-0 transition-all duration-300">
                    </a>
                </div>
                
                </div>

            <div class="hidden sm:flex sm:items-center sm:gap-4">
                
                <button @click="toggleTheme()" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 hover:text-yellow-500 transition focus:outline-none">
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                @if(auth()->user()->role === 'supervisor')
                    <div class="relative mr-2">
                        <a href="{{ route('supervisor.inbox') }}" class="p-1 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none relative block">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-600 ring-2 ring-white animate-pulse"></span>
                            @endif
                        </a>
                    </div>
                @endif

                <div class="relative ml-3" x-data="{ openUser: false }">
                    <button @click="openUser = ! openUser" class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white focus:outline-none transition">
                        <span>Hola, {{ Auth::user()->name }}</span>
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>

                    <div x-show="openUser" @click.away="openUser = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-100 dark:border-gray-700" style="display: none;">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Cuenta</p>
                            <p class="text-sm text-gray-800 dark:text-gray-200 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">Mi Perfil</a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>