<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QuickPark - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .glass { background: rgba(255, 255, 255, 0.45); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.3); border-radius: 10px; }
    </style>
</head>
<body x-data="{ sidebarExpanded: true }" class="bg-slate-50 font-sans antialiased text-slate-900 flex h-screen overflow-hidden selection:bg-indigo-100 selection:text-indigo-700">

@php
    $isDashboard = request()->routeIs('dashboard');

    $isUsers = request()->routeIs('users.*');
    $isUsersList = request()->routeIs('users.index') && !request()->has('create');
    $isUsersCreate = request()->routeIs('users.index') && request()->has('create');

    $isRoles = request()->routeIs('roles.*');
    $isRolesList = request()->routeIs('roles.index') && !request()->has('create');
    $isRolesCreate = request()->routeIs('roles.index') && request()->has('create');

    // Futuros módulos
    $isPrices = request()->is('precios*');
    $isRecords = request()->is('registros*');
    $isConfig = request()->is('configuraciones*');

    // Variable segura para obtener el nombre del rol (soporta tanto relaciones de modelos como strings simples)
    $userRole = is_object(Auth::user()->role) ? Auth::user()->role->name : Auth::user()->role;
@endphp

<aside :class="sidebarExpanded ? 'w-72' : 'w-20'" class="relative bg-white/70 backdrop-blur-xl border-r border-white/50 shadow-sm hidden md:flex flex-col h-full z-30 transition-all duration-300 ease-in-out">

    <button @click="sidebarExpanded = !sidebarExpanded" class="absolute -right-3 top-10 bg-white border border-slate-200 rounded-full p-1 shadow-sm text-slate-400 hover:text-indigo-600 z-50 transition-transform duration-300 focus:outline-none hover:scale-110" :class="!sidebarExpanded && 'rotate-180'">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </button>

    <div class="p-6 border-b border-slate-200/50 flex flex-col items-center">
        <a href="{{ route('dashboard') }}" class="block mb-6 transition-transform duration-300 hover:scale-[1.02] flex items-center justify-center h-20 w-full relative">

            <img x-show="sidebarExpanded"
                 x-transition.opacity.duration.300ms
                 src="{{ asset('img/minimalist--futuristic--quickpark-letter.svg.png') }}"
                 alt="QuickPark"
                 class="w-full max-w-[220px] object-contain drop-shadow-lg scale-125 origin-center absolute">

            <div x-show="!sidebarExpanded"
                 style="display: none;"
                 class="h-12 w-12 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-indigo-500/30 absolute">
                Q
            </div>
        </a>

        <div class="flex items-center bg-white/40 backdrop-blur-sm p-2 rounded-2xl border border-white/60 shadow-sm transition-all duration-300 w-full mt-2" :class="!sidebarExpanded ? 'justify-center' : 'space-x-3'">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex-shrink-0 flex items-center justify-center text-white font-bold shadow-md">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="truncate flex-1">
                <p class="text-sm font-bold text-slate-800 tracking-tight truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider truncate">{{ $userRole }}</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-2 custom-scrollbar">

        @if(in_array($userRole, ['Administrador', 'Cajero']))
            <a href="{{ route('dashboard') }}" class="group relative flex items-center px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ $isDashboard ? 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100/50' : 'text-slate-600 hover:bg-white/50 hover:text-indigo-600' }}" :class="!sidebarExpanded && 'justify-center'">
                <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-lg bg-indigo-600 transition-all duration-300 {{ $isDashboard ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-50 group-hover:opacity-100 group-hover:scale-y-100' }}"></div>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="ml-3 truncate">Mapa de Puestos</span>
            </a>

            <a href="{{ route('clients.index') }}" class="group relative flex items-center px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('clients.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100/50' : 'text-slate-600 hover:bg-white/50 hover:text-indigo-600' }}" :class="!sidebarExpanded && 'justify-center'">
                <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-lg bg-indigo-600 transition-all duration-300 {{ request()->routeIs('clients.*') ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-50 group-hover:opacity-100 group-hover:scale-y-100' }}"></div>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="ml-3 truncate">Directorio Clientes</span>
            </a>
        @endif


        @if(in_array($userRole, ['Administrador', 'Operador']))
            <div x-data="{ open: {{ $isUsers ? 'true' : 'false' }} }" class="space-y-1 mt-4">
                <button @click="if(!sidebarExpanded) { sidebarExpanded = true; open = true; } else { open = !open; }" class="group relative w-full flex items-center px-4 py-3 rounded-xl font-semibold transition-all focus:outline-none {{ $isUsers ? 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100/50' : 'text-slate-600 hover:bg-white/50 hover:text-indigo-600' }}" :class="!sidebarExpanded ? 'justify-center' : 'justify-between'">
                    <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-lg bg-indigo-600 transition-all duration-300 {{ $isUsers ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-50 group-hover:opacity-100 group-hover:scale-y-100' }}"></div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span x-show="sidebarExpanded" x-transition.opacity class="ml-3 truncate">Usuarios</span>
                    </div>
                    <svg x-show="sidebarExpanded" :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="sidebarExpanded && open" x-transition.opacity class="pl-12 pr-4 py-1.5 space-y-2">
                    <a href="{{ route('users.index') }}" class="block text-sm font-medium transition-colors relative before:content-[''] before:absolute before:-left-4 before:top-1/2 before:w-1.5 before:h-1.5 before:rounded-full before:-translate-y-1/2 {{ $isUsersList ? 'text-indigo-600 font-bold before:bg-indigo-600' : 'text-slate-500 hover:text-indigo-600 before:bg-slate-300 hover:before:bg-indigo-600' }}">Listado de Usuarios</a>
                    <a href="{{ route('users.index', ['create' => 'true']) }}" class="block text-sm font-medium transition-colors relative before:content-[''] before:absolute before:-left-4 before:top-1/2 before:w-1.5 before:h-1.5 before:rounded-full before:-translate-y-1/2 {{ $isUsersCreate ? 'text-indigo-600 font-bold before:bg-indigo-600' : 'text-slate-500 hover:text-indigo-600 before:bg-slate-300 hover:before:bg-indigo-600' }}">Agregar Usuario</a>
                </div>
            </div>

            <div x-data="{ open: {{ $isRoles ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="if(!sidebarExpanded) { sidebarExpanded = true; open = true; } else { open = !open; }" class="group relative w-full flex items-center px-4 py-3 rounded-xl font-semibold transition-all focus:outline-none {{ $isRoles ? 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100/50' : 'text-slate-600 hover:bg-white/50 hover:text-indigo-600' }}" :class="!sidebarExpanded ? 'justify-center' : 'justify-between'">
                    <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-lg bg-indigo-600 transition-all duration-300 {{ $isRoles ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-50 group-hover:opacity-100 group-hover:scale-y-100' }}"></div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span x-show="sidebarExpanded" x-transition.opacity class="ml-3 truncate">Roles</span>
                    </div>
                    <svg x-show="sidebarExpanded" :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="sidebarExpanded && open" x-transition.opacity class="pl-12 pr-4 py-1.5 space-y-2">
                    <a href="{{ route('roles.index') }}" class="block text-sm font-medium transition-colors relative before:content-[''] before:absolute before:-left-4 before:top-1/2 before:w-1.5 before:h-1.5 before:rounded-full before:-translate-y-1/2 {{ $isRolesList ? 'text-indigo-600 font-bold before:bg-indigo-600' : 'text-slate-500 hover:text-indigo-600 before:bg-slate-300 hover:before:bg-indigo-600' }}">Listado de Roles</a>
                    <a href="{{ route('roles.index', ['create' => 'true']) }}" class="block text-sm font-medium transition-colors relative before:content-[''] before:absolute before:-left-4 before:top-1/2 before:w-1.5 before:h-1.5 before:rounded-full before:-translate-y-1/2 {{ $isRolesCreate ? 'text-indigo-600 font-bold before:bg-indigo-600' : 'text-slate-500 hover:text-indigo-600 before:bg-slate-300 hover:before:bg-indigo-600' }}">Agregar Rol</a>
                </div>
            </div>

            <div x-data="{ open: {{ $isPrices ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="if(!sidebarExpanded) { sidebarExpanded = true; open = true; } else { open = !open; }" class="group relative w-full flex items-center px-4 py-3 text-slate-600 hover:bg-white/50 hover:text-indigo-600 rounded-xl font-semibold transition-all focus:outline-none" :class="!sidebarExpanded ? 'justify-center' : 'justify-between'">
                    <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-lg bg-indigo-600 transition-all duration-300 opacity-0 scale-y-50 group-hover:opacity-100 group-hover:scale-y-100"></div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zM9 16h6M9 12h6M9 8h6"/></svg>
                        <span x-show="sidebarExpanded" x-transition.opacity class="ml-3 truncate">Precios</span>
                    </div>
                    <svg x-show="sidebarExpanded" :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="sidebarExpanded && open" x-transition.opacity class="pl-12 pr-4 py-1.5 space-y-2">
                    <a href="{{ route('prices.index') }}" class="block text-sm font-medium {{ request()->routeIs('prices.*') ? 'text-indigo-600 font-bold before:bg-indigo-600' : 'text-slate-500 hover:text-indigo-600 before:bg-slate-300 hover:before:bg-indigo-600' }} relative before:content-[''] before:absolute before:-left-4 before:top-1/2 before:w-1.5 before:h-1.5 before:rounded-full before:-translate-y-1/2">Tarifas Actuales</a>
                </div>
            </div>

            <a href="{{ route('records.index') }}" class="group relative flex items-center px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('records.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100/50' : 'text-slate-600 hover:bg-white/50 hover:text-indigo-600' }}" :class="!sidebarExpanded && 'justify-center'">
                <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-lg bg-indigo-600 transition-all duration-300 {{ request()->routeIs('records.*') ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-50 group-hover:opacity-100 group-hover:scale-y-100' }}"></div>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="ml-3 truncate">Historial Vehículos</span>
            </a>

            <a href="{{ route('parking.spaces.index') }}" class="group relative flex items-center px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('parking.spaces.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100/50' : 'text-slate-600 hover:bg-white/50 hover:text-indigo-600' }}" :class="!sidebarExpanded && 'justify-center'">
                <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-lg bg-indigo-600 transition-all duration-300 {{ request()->routeIs('parking.spaces.*') ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-50 group-hover:opacity-100 group-hover:scale-y-100' }}"></div>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="ml-3 truncate">Gestión de Puestos</span>
            </a>

            <a href="{{ route('shifts.index') }}" class="group relative flex items-center px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('shifts.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100/50' : 'text-slate-600 hover:bg-white/50 hover:text-indigo-600' }}" :class="!sidebarExpanded && 'justify-center'">
                <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-lg bg-indigo-600 transition-all duration-300 {{ request()->routeIs('shifts.*') ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-50 group-hover:opacity-100 group-hover:scale-y-100' }}"></div>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="ml-3 truncate">Auditoría de Turnos</span>
            </a>

            <a href="{{ route('information.index') }}" class="group relative flex items-center px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('information.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100/50' : 'text-slate-600 hover:bg-white/50 hover:text-indigo-600' }}" :class="!sidebarExpanded && 'justify-center'">
                <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-lg bg-indigo-600 transition-all duration-300 {{ request()->routeIs('information.*') ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-50 group-hover:opacity-100 group-hover:scale-y-100' }}"></div>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="ml-3 truncate">Configuración</span>
            </a>

            <a href="{{ route('tasa.index') }}" class="group relative flex items-center px-4 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('tasa.*') ? 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100/50' : 'text-slate-600 hover:bg-white/50 hover:text-indigo-600' }}" :class="!sidebarExpanded && 'justify-center'">
                <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-lg bg-indigo-600 transition-all duration-300 {{ request()->routeIs('tasa.*') ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-50 group-hover:opacity-100 group-hover:scale-y-100' }}"></div>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-show="sidebarExpanded" x-transition.opacity.duration.300ms class="ml-3 truncate">Tasa del Día</span>
            </a>
        @endif

    </nav>

    <div class="p-4 border-t border-slate-200/50 mt-auto">
        <form method="POST" action="{{ route('logout') }}" x-data>
            @csrf
            <button type="submit" @click.prevent="$root.submit();" class="group flex w-full items-center justify-center gap-3 rounded-xl bg-slate-800 px-4 py-3 font-semibold text-white transition-all duration-300 hover:bg-red-600 hover:shadow-lg hover:shadow-red-500/30">
                <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span x-show="sidebarExpanded" class="truncate tracking-wide">Cerrar Sesión</span>
            </button>
        </form>
    </div>
</aside>

<main class="flex-1 flex flex-col h-full overflow-hidden relative">
    @yield('admin_content')
</main>

</body>
</html>
