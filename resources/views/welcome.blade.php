<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>HOLOCRON | Gestión Minera</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=orbitron:400,500,700,900|inter:300,400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-brand { font-family: 'Orbitron', sans-serif; }
            .font-body { font-family: 'Inter', sans-serif; }
            
            /* Fondo elegante con degradado sutil */
            body {
                background: radial-gradient(circle at center, #1a1a1a 0%, #000000 100%);
            }

            /* Efecto de Levitación para el Holocron */
            @keyframes levitate {
                0% { transform: translateY(0px); filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.1)); }
                50% { transform: translateY(-15px); filter: drop-shadow(0 0 30px rgba(255, 255, 255, 0.2)); }
                100% { transform: translateY(0px); filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.1)); }
            }
            .holocron-artifact {
                animation: levitate 6s ease-in-out infinite;
            }

            /* Efecto Cristal para tarjetas */
            .glass-card {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
        </style>
    </head>
    <body class="antialiased text-gray-300 min-h-screen flex flex-col">
        
        <nav class="w-full p-6 flex justify-between items-center z-50">
            <div class="flex items-center gap-3 opacity-80">
                <img src="{{ asset('img/logo-transparente.png') }}" alt="Logo" class="h-8 w-auto">
                <span class="font-brand text-xl font-bold tracking-widest text-white">HOLOCRON</span>
            </div>

            <div class="flex gap-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-body text-sm font-medium hover:text-white transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-body text-sm font-medium hover:text-white transition">Iniciar Sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="font-body text-sm font-medium px-4 py-2 border border-gray-600 rounded hover:border-white hover:text-white transition">Registrarse</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <main class="flex-1 flex flex-col items-center justify-center text-center px-4 relative overflow-hidden">
            
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-red-900/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 mb-8">
                <img src="{{ asset('img/logo-transparente.png') }}" 
                     alt="Holocron Artifact" 
                     class="w-48 md:w-64 h-auto holocron-artifact">
            </div>

            <h1 class="font-brand text-5xl md:text-7xl font-black text-white tracking-tight mb-4 relative z-10">
                HOLO<span class="text-red-700">CRON</span>
            </h1>

            <p class="font-body text-lg md:text-xl text-gray-400 max-w-2xl leading-relaxed mb-10 relative z-10 font-light">
                La custodia digital de tus operaciones mineras. <br>
                Seguridad, trazabilidad y gestión en un solo sistema.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 relative z-10">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-white text-black font-brand font-bold text-sm tracking-wider rounded hover:bg-gray-200 transition shadow-[0_0_20px_rgba(255,255,255,0.3)]">
                        ACCEDER AL SISTEMA
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-black font-brand font-bold text-sm tracking-wider rounded hover:bg-gray-200 transition shadow-[0_0_20px_rgba(255,255,255,0.3)]">
                        INGRESAR
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-3 bg-transparent border border-gray-600 text-white font-brand font-bold text-sm tracking-wider rounded hover:border-red-600 hover:text-red-500 transition">
                        CREAR CUENTA
                    </a>
                @endauth
            </div>

        </main>

        <footer class="w-full py-8 border-t border-white/5 bg-black/20 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 text-xs text-gray-500">
                
                <div class="text-center md:text-left">
                    <p class="font-bold text-gray-300 mb-2 uppercase tracking-wider">Seguridad</p>
                    <p>Encriptación de grado militar para documentos SST críticos.</p>
                </div>

                <div class="text-center">
                    <p>&copy; {{ date('Y') }} Holocron Systems.</p>
                    <p class="mt-1">Operaciones Mineras Eficientes.</p>
                </div>

                <div class="text-center md:text-right">
                    <p class="font-bold text-gray-300 mb-2 uppercase tracking-wider">Conformidad</p>
                    <p>Cumplimiento normativo D.S. 024-2016-EM</p>
                </div>

            </div>
        </footer>

    </body>
</html>