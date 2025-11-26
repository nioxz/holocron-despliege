<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <div class="relative overflow-hidden rounded-xl p-8 shadow-2xl">
            <div class="absolute inset-0 {{ Auth::user()->role === 'supervisor' ? 'bg-gradient-to-r from-indigo-900 to-purple-900' : 'bg-gradient-to-r from-blue-900 to-cyan-900' }}"></div>
            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-64 h-64 rounded-full bg-white opacity-5 blur-3xl"></div>
            
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h2 class="font-extrabold text-3xl text-white tracking-tight flex items-center gap-3">
                        {{ __('Centro de Comando SST') }}
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest border border-white/20 bg-white/10 text-white backdrop-blur-sm">
                            {{ Auth::user()->role === 'supervisor' ? 'SUPERVISOR' : 'OPERADOR' }}
                        </span>
                    </h2>
                    <p class="mt-2 text-lg text-gray-300 font-light">
                        @if(Auth::user()->role === 'supervisor')
                            Bienvenido, {{ Auth::user()->name }}. Control total de la operación.
                        @else
                            Hola, {{ Auth::user()->name }}. Tu seguridad es nuestra prioridad.
                        @endif
                    </p>
                </div>
                <div class="hidden md:block text-right text-white opacity-80">
                    <p class="text-sm uppercase tracking-widest">{{ now()->format('l, d F Y') }}</p>
                    <p class="text-2xl font-bold font-mono">{{ now()->format('H:i') }} <span class="text-sm">HRS</span></p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(Auth::user()->role === 'supervisor')
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border-l-4 border-yellow-400 hover:-translate-y-1 transition duration-300">
                        <div class="flex justify-between">
                            <div><p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase">Por Revisar</p><h3 class="text-5xl font-black text-gray-800 dark:text-white mt-2">{{ $pendientes ?? 0 }}</h3></div>
                            <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl text-yellow-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        </div>
                        <a href="{{ route('supervisor.inbox') }}" class="mt-4 inline-block text-sm text-yellow-600 dark:text-yellow-400 font-bold hover:underline">Ir a bandeja →</a>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border-l-4 border-green-500 hover:-translate-y-1 transition duration-300">
                        <div class="flex justify-between">
                            <div><p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase">Aprobados Global</p><h3 class="text-5xl font-black text-gray-800 dark:text-white mt-2">{{ $aprobados ?? 0 }}</h3></div>
                            <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-xl text-green-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        </div>
                    </div>
                    <a href="{{ route('export.csv') }}" class="group bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 shadow-lg text-white hover:-translate-y-1 transition duration-300">
                        <div><p class="text-indigo-200 text-xs font-bold uppercase">Data Analytics</p><h3 class="text-2xl font-bold mt-2">Descargar Data</h3></div>
                        <div class="mt-4 flex justify-end"><svg class="w-8 h-8 opacity-80 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></div>
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg lg:col-span-2 border border-gray-100 dark:border-gray-700">
                        <h3 class="font-bold text-gray-800 dark:text-white mb-4 text-lg flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                            Resumen de Gestión Global
                        </h3>
                        <div class="relative h-72 w-full flex justify-center items-center">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <div class="space-y-4">
                        
                        <div class="p-5 rounded-2xl bg-gray-900 text-white shadow-xl border border-gray-700">
                            <h4 class="font-bold text-xs uppercase text-gray-400 mb-4 tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                Administración
                            </h4>
                            
                            @if(Auth::user()->email === 'admin@holocron.com')
                                <a href="{{ route('admin.create-company') }}" class="block w-full mb-3 p-2 bg-indigo-600 hover:bg-indigo-500 rounded text-center text-xs font-bold transition border border-indigo-400">
                                    + Crear Nueva Empresa (Dueño)
                                </a>
                            @endif
                            
                            <a href="{{ route('company.staff') }}" class="block w-full p-2 bg-gray-700 hover:bg-gray-600 rounded text-center text-xs font-bold transition border border-gray-500">
                                👥 Gestionar Personal
                            </a>
                        </div>

                        <div class="p-5 rounded-2xl bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-white shadow-lg border border-gray-300 dark:border-gray-700">
                            <h4 class="font-bold text-xs uppercase text-gray-500 dark:text-gray-400 mb-4 tracking-widest">Operaciones</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('iperc.create') }}" class="p-2 bg-white dark:bg-gray-600 hover:bg-blue-500 hover:text-white rounded text-center text-xs font-bold shadow-sm">IPERC</a>
                                <a href="{{ route('ats.create') }}" class="p-2 bg-white dark:bg-gray-600 hover:bg-orange-500 hover:text-white rounded text-center text-xs font-bold shadow-sm">ATS</a>
                                <a href="{{ route('checklist.create') }}" class="p-2 bg-white dark:bg-gray-600 hover:bg-green-500 hover:text-white rounded text-center text-xs font-bold shadow-sm">Checklist</a>
                                <a href="{{ route('petar.create') }}" class="p-2 bg-white dark:bg-gray-600 hover:bg-red-500 hover:text-white rounded text-center text-xs font-bold shadow-sm">PETAR</a>
                                <a href="{{ route('pets.index') }}" class="col-span-2 p-2 bg-white dark:bg-gray-600 hover:bg-purple-500 hover:text-white rounded text-center text-xs font-bold shadow-sm">Base PETS</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700"><h3 class="font-bold text-gray-800 dark:text-white mb-4 text-xs uppercase flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-purple-500"></span> Tasa de Rechazo</h3><ul class="divide-y divide-gray-100 dark:divide-gray-700">@foreach($docStats as $type => $stat)<li class="py-2 flex justify-between items-center"><span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $type }}</span><div class="text-right"><span class="block text-xs font-bold {{ $stat['rate'] > 0 ? 'text-red-500' : 'text-green-500' }}">{{ $stat['rate'] }}% Rechazo</span><span class="text-[10px] text-gray-400">Total: {{ $stat['total'] }}</span></div></li>@endforeach</ul></div>
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700"><h3 class="font-bold text-gray-800 dark:text-white mb-4 text-xs uppercase flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-indigo-500"></span> Áreas Activas</h3><ul class="divide-y divide-gray-100 dark:divide-gray-700">@foreach($topAreas as $area => $count)<li class="py-2 flex justify-between"><span class="text-xs text-gray-600 dark:text-gray-300 truncate w-2/3">{{ $area ?: 'N/A' }}</span><span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ $count }}</span></li>@endforeach</ul></div>
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700"><h3 class="font-bold text-gray-800 dark:text-white mb-4 text-xs uppercase flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> Riesgos</h3><ul class="divide-y divide-gray-100 dark:divide-gray-700">@foreach($topPeligros as $peligro => $count)<li class="py-2 flex justify-between"><span class="text-xs text-gray-600 dark:text-gray-300 truncate w-2/3">{{ $peligro ?: 'N/A' }}</span><span class="text-xs font-bold text-red-600 dark:text-red-400">{{ $count }}</span></li>@endforeach</ul></div>
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700"><h3 class="font-bold text-gray-800 dark:text-white mb-4 text-xs uppercase flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span> Top Trabajadores</h3><ul class="divide-y divide-gray-100 dark:divide-gray-700">@foreach($topWorkers as $worker => $count)<li class="py-2 flex justify-between"><span class="text-xs text-gray-600 dark:text-gray-300">{{ $worker }}</span><span class="text-xs font-bold text-green-600 dark:text-green-400">{{ $count }}</span></li>@endforeach</ul></div>
                </div>

                <script>document.addEventListener("DOMContentLoaded",function(){const t=document.getElementById("statusChart");if(t){const o=t.getContext("2d");window.myChart instanceof Chart&&window.myChart.destroy();const e=document.documentElement.classList.contains("dark")?"#e5e7eb":"#374151";window.myChart=new Chart(o,{type:"doughnut",data:{labels:["Aprobados","Rechazados","Pendientes"],datasets:[{data:[{{$aprobados??0}},{{$rechazados??0}},{{$pendientes??0}}],backgroundColor:["#10B981","#EF4444","#F59E0B"],borderWidth:0,hoverOffset:15}]},options:{responsive:!0,maintainAspectRatio:!1,plugins:{legend:{position:"right",labels:{color:e,font:{size:12,family:"'Inter', sans-serif"},padding:20}}},cutout:"70%",layout:{padding:10}}})}});</script>
            @else
                <div class="bg-gradient-to-r from-blue-800 to-indigo-900 rounded-3xl p-8 mb-10 shadow-2xl text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <h1 class="text-3xl font-extrabold tracking-tight">¡Hola, {{ Auth::user()->name }}! 👋</h1>
                        <p class="mt-2 text-blue-100 max-w-xl text-lg">Bienvenido a tu espacio de trabajo seguro.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <a href="{{ route('iperc.create') }}" class="group bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-transparent hover:border-blue-500"><h4 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-blue-500">IPERC Continuo</h4><p class="text-sm text-gray-500 dark:text-gray-400">Matriz de Riesgos</p></a>
                    <a href="{{ route('ats.create') }}" class="group bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-transparent hover:border-orange-500"><h4 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-orange-500">ATS</h4><p class="text-sm text-gray-500 dark:text-gray-400">Análisis Seguro</p></a>
                    <a href="{{ route('checklist.create') }}" class="group bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-transparent hover:border-green-500"><h4 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-green-500">Checklist</h4><p class="text-sm text-gray-500 dark:text-gray-400">Pre-uso Equipos</p></a>
                    <a href="{{ route('petar.create') }}" class="group bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-transparent hover:border-red-600"><h4 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-red-500">PETAR</h4><p class="text-sm text-gray-500 dark:text-gray-400">Alto Riesgo</p></a>
                </div>
                <div class="bg-gray-900 rounded-2xl p-6 shadow-lg text-white flex justify-between items-center">
                    <div><h3 class="font-bold">Resumen</h3><p class="text-xs text-gray-400">Mis documentos</p></div>
                    <div class="flex gap-6"><div class="text-center"><p class="text-2xl font-bold text-yellow-400">{{ $pendientes ?? 0 }}</p><p class="text-[10px] uppercase">Pend</p></div><div class="text-center"><p class="text-2xl font-bold text-green-400">{{ $aprobados ?? 0 }}</p><p class="text-[10px] uppercase">Ok</p></div></div>
                    <a href="{{ route('history') }}" class="bg-white/10 px-4 py-2 rounded text-sm hover:bg-white/20">Historial</a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>