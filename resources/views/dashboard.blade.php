@extends('layouts.admin')
@section('title', 'Panel Operativo')

@section('admin_content')
    <div x-data="parkingOperations()" class="flex flex-col h-full relative">

        <header class="h-24 glass border-b border-white/50 px-8 flex items-center justify-between flex-shrink-0 z-10">
            <div>
                <h1 class="font-poppins text-2xl font-bold text-slate-900">Mapa de Aforos</h1>
                <p class="text-sm font-medium text-slate-500">Gestión física en tiempo real</p>
            </div>

            @php
                $total = $spaces->count();
                $available = $spaces->where('status', 'DISPONIBLE')->count();
                $occupied = $total - $available;

                // Verificamos si el cajero tiene un turno abierto
                $activeShift = \App\Models\Shift::where('user_id', auth()->id())->where('status', 'ABIERTO')->first();
            @endphp

            <div class="flex items-center gap-6">
                @if($activeShift)
                    <button @click="showCloseShiftModal = true" class="group relative flex items-center gap-3 overflow-hidden rounded-full bg-gradient-to-r from-red-600 to-rose-600 px-6 py-2.5 font-bold text-white shadow-[0_0_15px_rgba(220,38,38,0.4)] transition-all duration-300 hover:scale-105 hover:shadow-[0_0_25px_rgba(220,38,38,0.6)] focus:outline-none">
                        <span class="relative flex h-3 w-3">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span>
                            <span class="relative inline-flex h-3 w-3 rounded-full bg-white"></span>
                        </span>
                        <span class="tracking-wide">Cerrar Turno</span>
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </button>
                @else
                    <button @click="showOpenShiftModal = true" class="group relative flex items-center gap-3 overflow-hidden rounded-full bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-2.5 font-bold text-white shadow-[0_0_15px_rgba(79,70,229,0.4)] transition-all duration-300 hover:scale-105 hover:shadow-[0_0_25px_rgba(79,70,229,0.6)] focus:outline-none">
                        <svg class="w-5 h-5 transition-transform group-hover:-rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        <span class="tracking-wide">Abrir Turno</span>
                    </button>
                @endif

                <div class="hidden sm:flex gap-3">
                    <div class="bg-white/60 border border-white px-5 py-2 rounded-xl shadow-sm text-center min-w-[90px]">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</span>
                        <span class="block text-lg font-poppins font-bold text-slate-800">{{ $total }}</span>
                    </div>
                    <div class="bg-green-50 border border-green-100/70 px-5 py-2 rounded-xl shadow-sm text-center min-w-[90px]">
                        <span class="block text-[10px] font-bold text-green-500 uppercase tracking-wider">Libres</span>
                        <span class="block text-lg font-poppins font-bold text-green-600">{{ $available }}</span>
                    </div>
                    <div class="bg-red-50 border border-red-100/70 px-5 py-2 rounded-xl shadow-sm text-center min-w-[90px]">
                        <span class="block text-[10px] font-bold text-red-500 uppercase tracking-wider">Ocupados</span>
                        <span class="block text-lg font-poppins font-bold text-red-600">{{ $occupied }}</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar z-0 bg-slate-100/30">

            @if(session('success'))
                <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-slate-900/95 backdrop-blur-xl border border-slate-700 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3"
                     x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     x-init="setTimeout(() => show = false, 4000)">
                    <div class="w-8 h-8 bg-green-500/20 text-green-400 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="font-bold text-sm tracking-wide">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-red-900/95 backdrop-blur-xl border border-red-700 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3"
                     x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     x-init="setTimeout(() => show = false, 5000)">
                    <div class="w-8 h-8 bg-red-500/20 text-red-400 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="font-bold text-sm tracking-wide">{{ $errors->first() }}</span>
                </div>
            @endif

                <div class="bg-white/40 backdrop-blur-2xl p-8 sm:p-12 rounded-[3rem] shadow-[0_20px_50px_rgba(15,23,42,0.05)] border border-white/80 mb-10 relative overflow-hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-32 bg-gradient-to-b from-indigo-500/10 to-transparent blur-3xl pointer-events-none"></div>

                    <div class="text-center mb-10 relative z-10 flex flex-col items-center">
                        <h2 class="font-poppins text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">Seleccione un puesto</h2>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2 mb-6">Haga clic en un espacio libre para registrar entrada</p>

                        <div class="relative w-full max-w-sm mx-auto group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-qp-indigo transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <input type="text" x-model="searchQuery" placeholder="Buscar número de puesto..."
                                   class="w-full pl-12 pr-10 py-3.5 bg-white/50 backdrop-blur-md border border-slate-200/80 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-qp-indigo transition-all font-bold text-slate-700 shadow-[0_5px_15px_rgba(15,23,42,0.05)] placeholder-slate-400 outline-none">

                            <button type="button" x-show="searchQuery !== ''" @click="searchQuery = ''" style="display: none;" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-red-500 transition-colors focus:outline-none">
                                <svg class="h-5 w-5 bg-slate-100 rounded-full p-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-12 relative z-10">

                        @foreach($spaces as $space)
                            <div x-show="searchQuery === '' || '{{ strtolower($space->space_number) }}'.includes(searchQuery.toLowerCase())"
                                 x-transition.opacity.duration.300ms
                                 class="relative flex flex-col items-center group cursor-pointer perspective-1000">

                                <div class="w-2/3 h-3.5 bg-gradient-to-b from-slate-200 to-slate-300 rounded-t-lg shadow-[inset_0_-2px_4px_rgba(0,0,0,0.1)] mb-1.5 z-0"></div>

                                @if($space->status == 'DISPONIBLE')
                                    <button @click="openDispensadora('{{ $space->space_number }}', {{ $space->id }})" class="relative w-full aspect-[3/4] bg-gradient-to-b from-green-50/40 to-white/80 backdrop-blur-md border-x-4 border-dashed border-green-300/80 hover:border-green-400 focus:outline-none overflow-hidden rounded-b-2xl flex flex-col items-center justify-center transition-all duration-500 shadow-[inset_0_-20px_30px_rgba(255,255,255,0.9)] hover:shadow-[0_15px_30px_rgba(34,197,94,0.15)] group-hover:-translate-y-1">

                                        <span class="font-poppins font-black text-slate-300 text-6xl group-hover:text-green-500 transition-colors duration-300 select-none drop-shadow-sm">{{ $space->space_number }}</span>

                                        <div class="absolute bottom-5 flex flex-col items-center">
                                            <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(74,222,128,0.8)]"></div>
                                        </div>

                                        <div class="absolute bottom-0 w-full h-1 bg-gradient-to-t from-green-500 to-transparent opacity-0 group-hover:opacity-60 transition-opacity duration-500"></div>
                                    </button>
                                @else
                                    <button @click="openValidadora('{{ $space->space_number }}', {{ $space->id }})" class="relative w-full aspect-[3/4] bg-gradient-to-b from-slate-200/60 to-slate-100/40 backdrop-blur-md border-x-4 border-solid border-slate-300/80 focus:outline-none overflow-hidden rounded-b-2xl flex flex-col items-center justify-center transition-all duration-500 shadow-inner group-hover:shadow-[0_20px_40px_rgba(79,70,229,0.2)] group-hover:border-indigo-400/60 group-hover:-translate-y-1">
                                        <span class="absolute font-poppins font-black text-slate-300/40 text-6xl select-none z-0">{{ $space->space_number }}</span>

                                        <img src="{{ asset('img/vehicle.png') }}" class="absolute z-10 w-24 drop-shadow-[0_25px_20px_rgba(0,0,0,0.4)] transform transition-all duration-700 group-hover:scale-105 group-hover:-translate-y-3" alt="Vehículo">

                                        <div class="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 bg-slate-900/60 backdrop-blur-[3px] z-20">
                                            <div class="w-12 h-12 bg-gradient-to-br from-qp-blue to-qp-indigo rounded-full flex items-center justify-center shadow-[0_0_20px_rgba(79,70,229,0.6)] mb-3 transform translate-y-6 group-hover:translate-y-0 transition-all duration-500 ease-out">
                                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            </div>
                                            <span class="font-black text-white text-[10px] tracking-widest uppercase bg-slate-900 px-4 py-1.5 rounded-full border border-indigo-500 shadow-2xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 delay-75 ease-out">Facturar</span>
                                        </div>
                                        <div class="absolute bottom-0 w-full h-1 bg-gradient-to-t from-indigo-500 to-transparent opacity-0 group-hover:opacity-70 transition-opacity duration-500 z-10"></div>
                                    </button>
                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>

            <footer class="mt-16 text-center text-xs font-medium text-slate-400 tracking-wide pb-4">
                Siempre hay lugar para que vuelvas. <br>
                <span class="text-slate-300 font-normal">Copyright &copy; 2026 QuickPark. Todos los derechos reservados.</span>
            </footer>
        </div>

        <div x-show="showDispensadora" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-2xl p-4" @click.away="closeDispensadora()" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="relative bg-white/90 backdrop-blur-xl rounded-[2.5rem] border border-white/60 shadow-2xl overflow-hidden">

                    <div class="flex items-center justify-between p-8 border-b border-slate-100 bg-white/40">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-qp-blue to-qp-indigo rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-poppins font-black text-slate-800 tracking-tight">Dispensadora</h3>
                                <p class="text-xs font-bold text-qp-indigo uppercase tracking-widest mt-0.5">Entrada de Vehículo • Puesto <span x-text="selectedSpace"></span></p>
                            </div>
                        </div>
                        <button @click="closeDispensadora()" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:text-red-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-8">
                        <form method="POST" action="{{ route('tickets.storeEntry') }}" @keydown.enter.prevent>
                            @csrf
                            <input type="hidden" name="parking_space_id" :value="selectedSpaceId">

                            <div class="mb-8">
                                <label class="block text-sm font-semibold text-slate-700 ml-2 mb-2">Matrícula / Placa</label>
                                <div class="flex gap-3">
                                    <input type="text" name="plate" x-model="form.plate" required style="text-transform: uppercase" class="w-full px-5 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-qp-indigo transition-all font-black text-2xl tracking-widest text-slate-800 uppercase shadow-inner">

                                    <button type="button" @click="startCamera()" class="px-5 bg-white border border-slate-200 text-qp-indigo hover:bg-indigo-50 rounded-2xl transition-all shadow-sm group">
                                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </button>

                                    <button type="button" @click="searchClient()" :disabled="searching" class="px-6 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all disabled:opacity-50 shadow-lg shadow-slate-900/20">
                                        <span x-show="!searching">Buscar</span>
                                        <svg x-show="searching" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div x-show="cameraOpen" x-transition class="mb-8 relative rounded-[2rem] overflow-hidden bg-slate-950 aspect-video border-4 border-white shadow-2xl">
                                <video x-ref="videoElement" autoplay playsinline class="w-full h-full object-cover opacity-80"></video>
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-3/4 h-1/2 border-2 border-qp-blue border-dashed rounded-3xl bg-qp-blue/5"></div>
                                </div>
                                <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-4 px-6">
                                    <button type="button" @click="stopCamera()" class="px-6 py-3 bg-red-500/90 backdrop-blur-md text-white rounded-xl font-bold text-sm shadow-xl">Cancelar</button>
                                    <button type="button" @click="captureAndScan()" class="px-8 py-3 bg-qp-indigo text-white rounded-xl font-bold text-sm flex items-center gap-2 shadow-xl shadow-indigo-500/40">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /></svg>
                                        Escanear Placa
                                    </button>
                                </div>
                            </div>

                            <div x-show="plateProcessed" x-transition class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-6 bg-slate-50/50 border border-slate-200/60 rounded-[2rem]">
                                    <div class="space-y-1">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Cédula</label>
                                        <div class="relative">
                                            <input type="text" name="document" x-model="form.document" @input="searchClientData()" @blur="searchClientData()" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-qp-indigo transition-all font-bold text-slate-700 shadow-sm">
                                            <div x-show="searchingClient" class="absolute right-3 top-2.5">
                                                <svg class="animate-spin h-5 w-5 text-qp-indigo" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nombre Completo</label>
                                        <input type="text" name="name" x-model="form.name" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-qp-indigo transition-all font-bold text-slate-700 shadow-sm">
                                    </div>
                                    <div class="md:col-span-2 space-y-1">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Teléfono Móvil</label>
                                        <input type="text" name="phone" x-model="form.phone" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-qp-indigo transition-all font-bold text-slate-700 shadow-sm">
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-2">
                                    <button type="button" @click="closeDispensadora()" class="px-8 py-4 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Cancelar</button>
                                    <button type="submit" class="px-10 py-4 bg-gradient-to-r from-qp-blue to-qp-indigo text-white font-bold rounded-2xl shadow-xl shadow-indigo-500/30 transform hover:-translate-y-1 transition-all">
                                        Registrar Entrada
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showValidadora" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-4xl p-4" @click.away="closeValidadora()" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="relative bg-white/95 backdrop-blur-xl rounded-[2rem] shadow-2xl overflow-hidden border border-white">

                    <div class="flex items-center justify-between p-6 border-b border-slate-100 bg-white/60">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-poppins font-black text-slate-800 tracking-tight">Facturación de Salida</h3>
                                <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-0.5">Puesto <span x-text="selectedSpace"></span></p>
                            </div>
                        </div>
                        <button @click="closeValidadora()" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors focus:outline-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-8 bg-slate-50/50">
                        <div x-show="loadingCheckout" class="flex flex-col items-center justify-center py-12">
                            <div class="w-10 h-10 border-4 border-indigo-100 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
                            <p class="font-bold text-slate-500 text-sm tracking-wide">Calculando tarifas e importes...</p>
                        </div>

                        <div x-show="!loadingCheckout" class="grid grid-cols-1 md:grid-cols-2 gap-10">

                            <div class="flex flex-col gap-4">
                                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-blue-500"></div>

                                    <div class="flex justify-between items-start mb-6">
                                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Resumen del Servicio</h4>
                                        <a :href="`/tickets/${checkoutData.ticket_id}/imprimir`" target="_blank" class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition-colors text-[10px] font-bold flex items-center gap-1.5 uppercase tracking-wider" title="Reimprimir Ticket">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Ticket
                                        </a>
                                    </div>

                                    <div class="space-y-3.5 text-sm">
                                        <div class="flex justify-between items-center"><span class="text-slate-500 font-semibold">Cliente:</span> <span class="font-bold text-slate-800" x-text="checkoutData.client_name"></span></div>
                                        <div class="flex justify-between items-center"><span class="text-slate-500 font-semibold">CI:</span> <span class="font-bold text-slate-800" x-text="checkoutData.client_document"></span></div>
                                        <div class="flex justify-between items-center"><span class="text-slate-500 font-semibold">Teléfono:</span> <span class="font-bold text-slate-800" x-text="checkoutData.client_phone"></span></div>
                                        <div class="flex justify-between items-center"><span class="text-slate-500 font-semibold">Placa:</span> <span class="font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-lg tracking-widest uppercase" x-text="checkoutData.plate"></span></div>

                                        <div class="pt-4 mt-2 border-t border-slate-100 space-y-2">
                                            <div class="flex justify-between items-center text-xs"><span class="text-slate-400 font-semibold uppercase tracking-wide">Hora Ingreso:</span> <span class="font-bold text-slate-600" x-text="checkoutData.entry_time"></span></div>
                                            <div class="flex justify-between items-center text-xs"><span class="text-slate-400 font-semibold uppercase tracking-wide">Estadía Total:</span> <span class="font-black text-indigo-600 bg-white" x-text="checkoutData.duration"></span></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-xl flex items-center justify-between border border-slate-700 relative overflow-hidden">
                                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full blur-xl"></div>
                                    <span class="font-bold text-slate-400 uppercase tracking-widest text-xs relative z-10">Monto Total</span>
                                    <span class="font-poppins font-black text-4xl text-white relative z-10">$<span x-text="checkoutData.total_amount"></span></span>
                                </div>
                            </div>

                            <div class="flex flex-col h-full justify-between">
                                <form id="checkoutForm" method="POST" action="{{ route('tickets.processCheckout') }}" class="flex-1 flex flex-col">
                                    @csrf
                                    <input type="hidden" name="ticket_id" :value="checkoutData.ticket_id">

                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-widest">Métodos de Pago</h4>
                                        <button type="button" @click="addPayment()" class="text-[10px] font-bold text-white bg-slate-800 hover:bg-slate-700 px-3 py-1.5 rounded-lg transition-colors uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                            Añadir
                                        </button>
                                    </div>

                                    <div class="flex-1 overflow-y-auto space-y-3 max-h-56 custom-scrollbar pr-2 mb-6">
                                        <template x-for="(payment, index) in payments" :key="index">
                                            <div class="flex gap-0 items-stretch bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 transition-all">
                                                <input type="hidden" :name="'payments['+index+'][method]'" :value="payment.method">
                                                <input type="hidden" :name="'payments['+index+'][amount]'" :value="payment.amount">

                                                <select x-model="payment.method" class="w-5/12 px-4 py-3 text-sm bg-slate-50 border-r border-slate-200 outline-none font-semibold text-slate-700 cursor-pointer">
                                                    <option value="Efectivo">Efectivo</option>
                                                    <option value="Tarjeta">Punto / Tarjeta</option>
                                                    <option value="Pago Móvil">Pago Móvil</option>
                                                    <option value="Divisas">Divisas</option>
                                                </select>

                                                <div class="relative w-7/12 flex items-center">
                                                    <span class="absolute left-4 font-black text-slate-400">$</span>
                                                    <input type="number" step="0.01" min="0" x-model.number="payment.amount" class="w-full pl-8 pr-12 py-3 text-lg bg-white outline-none font-black text-slate-800">

                                                    <button type="button" @click="removePayment(index)" class="absolute right-2 w-8 h-8 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" x-show="payments.length > 1">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="pt-4 border-t border-slate-200">
                                        <div class="flex justify-between items-center mb-5 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-2">
                                                Balance
                                                <span x-show="remainingBalance == 0" class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            </span>
                                            <span class="font-black text-xl flex items-baseline gap-1" :class="remainingBalance > 0 ? 'text-amber-500' : (remainingBalance < 0 ? 'text-indigo-500' : 'text-emerald-500')">
                                                $<span x-text="Math.abs(remainingBalance).toFixed(2)"></span>
                                                <span class="text-[10px] uppercase font-bold tracking-widest" x-text="remainingBalance > 0 ? 'Por Pagar' : (remainingBalance < 0 ? 'Vuelto' : 'Completo')"></span>
                                            </span>
                                        </div>

                                        <div class="flex flex-col gap-4 w-full">
                                            <div class="flex justify-between items-center w-full gap-4">
                                                <button type="button" @click="closeValidadora()" class="px-6 py-3.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cancelar</button>
                                                <button type="submit" :disabled="remainingBalance > 0" class="flex-1 py-3.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none transform hover:-translate-y-0.5">
                                                    Procesar y Facturar
                                                </button>
                                            </div>

                                            <div class="flex justify-between items-center w-full px-2">
                                                <button type="button" @click="cancelTicket()" class="text-[11px] font-bold text-slate-400 hover:text-red-500 transition-colors uppercase tracking-wider flex items-center gap-1.5 group">
                                                    <svg class="w-3.5 h-3.5 group-hover:-rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Anular Error
                                                </button>
                                                <button type="button" @click="$refs.waiveForm.submit()" class="text-[11px] font-bold text-indigo-500 hover:text-indigo-700 transition-colors uppercase tracking-wider flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Absolver Pago (VIP)
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="cancelForm" method="POST" :action="`/tickets/${checkoutData.ticket_id}/cancelar`" class="hidden">@csrf</form>
                                <form x-ref="waiveForm" method="POST" action="{{ route('tickets.waive') }}" class="hidden">
                                    @csrf
                                    <input type="hidden" name="ticket_id" :value="checkoutData.ticket_id">
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div x-show="showOpenShiftModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md" x-transition.opacity>
        <div class="relative w-full max-w-sm p-4" @click.away="showOpenShiftModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white/90 backdrop-blur-xl rounded-[2rem] border border-white shadow-2xl p-8">
                <h3 class="text-xl font-poppins font-bold text-slate-800 mb-2">Apertura de Caja</h3>
                <p class="text-sm text-slate-500 mb-6">Ingresa el fondo inicial o base con el que empiezas tu turno.</p>

                <form method="POST" action="{{ route('shifts.open') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 ml-1 mb-1">Monto Inicial (Fondo)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-lg">$</span>
                            <input type="number" step="0.01" min="0" name="opening_amount" required value="0.00" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-xl text-slate-800">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showOpenShiftModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl">Iniciar Turno</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <div x-show="showOpenShiftModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-md p-4" @click.away="showOpenShiftModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] border border-indigo-100/50 shadow-2xl p-8 sm:p-10">

                    <header class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner shrink-0">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-poppins font-black text-slate-800 tracking-tight">Apertura de Caja</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Inicio de operaciones</p>
                        </div>
                    </header>

                    <form method="POST" action="{{ route('shifts.open') }}" class="space-y-6">
                        @csrf

                        <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 transition-all">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Fondo Inicial</label>
                            <span class="text-[9px] text-slate-400 font-semibold mb-3 block">Dinero base en caja para dar vueltos</span>
                            <div class="flex items-center gap-2">
                                <span class="text-slate-400 font-bold text-2xl">$</span>
                                <input type="number" step="0.01" min="0" name="opening_amount" required value="0.00" class="w-full bg-transparent border-none p-0 focus:ring-0 font-poppins font-black text-4xl text-slate-800 outline-none">
                            </div>
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row-reverse gap-3 justify-end">
                            <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-qp-blue to-qp-indigo text-white font-black rounded-2xl shadow-xl shadow-indigo-500/30 transform hover:-translate-y-1 transition-all uppercase tracking-widest text-sm text-center">
                                Iniciar Turno
                            </button>
                            <button type="button" @click="showOpenShiftModal = false" class="w-full sm:w-auto px-8 py-4 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-2xl transition-colors text-center">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showCloseShiftModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-xl p-4" @click.away="showCloseShiftModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] border border-red-100/50 shadow-2xl p-8 sm:p-10">

                    <header class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center shadow-inner shrink-0">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-poppins font-black text-slate-800 tracking-tight">Cierre de Turno</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Contabilización final de valores</p>
                        </div>
                    </header>

                    <form method="POST" action="{{ route('shifts.close') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div class="bg-white/60 backdrop-blur-sm p-5 rounded-2xl border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-rose-500 transition-all">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Efectivo Físico</label>
                                <span class="text-[9px] text-slate-400 font-semibold mb-2 block">(Incluye Fondo)</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 font-bold text-xl">$</span>
                                    <input type="number" step="0.01" min="0" name="declared[Efectivo]" value="0.00" required class="w-full bg-transparent border-none p-0 focus:ring-0 font-poppins font-black text-2xl text-slate-800 outline-none">
                                </div>
                            </div>

                            <div class="bg-white/60 backdrop-blur-sm p-5 rounded-2xl border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-rose-500 transition-all">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Punto de Venta</label>
                                <span class="text-[9px] text-slate-400 font-semibold mb-2 block">(Lote de Tarjetas)</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 font-bold text-xl">$</span>
                                    <input type="number" step="0.01" min="0" name="declared[Tarjeta]" value="0.00" required class="w-full bg-transparent border-none p-0 focus:ring-0 font-poppins font-black text-2xl text-slate-800 outline-none">
                                </div>
                            </div>

                            <div class="bg-white/60 backdrop-blur-sm p-5 rounded-2xl border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-rose-500 transition-all">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pago Móvil</label>
                                <span class="text-[9px] text-slate-400 font-semibold mb-2 block">(Transferencias)</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 font-bold text-xl">$</span>
                                    <input type="number" step="0.01" min="0" name="declared[Pago Móvil]" value="0.00" required class="w-full bg-transparent border-none p-0 focus:ring-0 font-poppins font-black text-2xl text-slate-800 outline-none">
                                </div>
                            </div>

                            <div class="bg-white/60 backdrop-blur-sm p-5 rounded-2xl border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-rose-500 transition-all">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Divisas</label>
                                <span class="text-[9px] text-slate-400 font-semibold mb-2 block">(Otras Monedas)</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 font-bold text-xl">$</span>
                                    <input type="number" step="0.01" min="0" name="declared[Divisas]" value="0.00" required class="w-full bg-transparent border-none p-0 focus:ring-0 font-poppins font-black text-2xl text-slate-800 outline-none">
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row-reverse gap-3 justify-end">
                            <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-red-600 to-rose-600 text-white font-black rounded-2xl shadow-xl shadow-rose-500/30 transform hover:-translate-y-1 transition-all uppercase tracking-widest text-sm text-center">
                                Imprimir Reporte Z
                            </button>
                            <button type="button" @click="showCloseShiftModal = false" class="w-full sm:w-auto px-8 py-4 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-2xl transition-colors text-center">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================== -->
    <!-- MODALES DE ÉXITO E IMPRESIÓN (DISEÑO PREMIUM)  -->
    <!-- ============================================== -->

    @if(session('print_ticket_url'))
        <div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/80 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-sm p-4">
                <div class="bg-white/90 backdrop-blur-xl rounded-[3rem] shadow-2xl p-10 text-center border border-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-qp-indigo/10 rounded-full blur-3xl"></div>

                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-gradient-to-br from-qp-blue to-qp-indigo text-white rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-xl shadow-indigo-500/40 transform rotate-3">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                        </div>
                        <h3 class="text-2xl font-poppins font-black text-slate-900 tracking-tight mb-2">¡Ingreso Exitoso!</h3>
                        <p class="text-sm text-slate-500 mb-10 font-medium leading-relaxed">El vehículo ya está en el sistema. Procede a entregar el ticket físico.</p>

                        <div class="flex flex-col gap-3">
                            <a href="{{ session('print_ticket_url') }}" target="_blank" @click="show = false" class="w-full py-5 bg-slate-900 text-white font-bold rounded-2xl shadow-xl hover:bg-slate-800 transition-all flex items-center justify-center gap-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Imprimir Ticket
                            </a>
                            <button @click="show = false" class="py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Cerrar Ventana</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('print_invoice_url'))
        <div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/80 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-sm p-4" @click.away="show = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="bg-white/95 backdrop-blur-xl rounded-[2rem] shadow-2xl p-8 text-center border border-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl"></div>

                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-700 text-white rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-indigo-500/30">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-poppins font-black text-slate-800 tracking-tight mb-1">¡Cobro Exitoso!</h3>
                        <p class="text-xs text-slate-500 mb-8 font-semibold">El puesto ha sido liberado. Imprima la factura final.</p>

                        <div class="flex gap-3">
                            <button @click="show = false" class="px-5 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cerrar</button>
                            <a href="{{ session('print_invoice_url') }}" target="_blank" @click="show = false" class="flex-1 py-3 px-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 rounded-xl flex items-center justify-center gap-2 transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Factura
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('print_z_report_url'))
        <div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/80 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-sm p-4" @click.away="show = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="bg-white/95 backdrop-blur-xl rounded-[2rem] shadow-2xl p-8 text-center border border-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-slate-500/10 rounded-full blur-2xl"></div>

                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-slate-700 to-slate-900 text-white rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-slate-900/30 border border-slate-600">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-poppins font-black text-slate-800 tracking-tight mb-1">Arqueo Guardado</h3>
                        <p class="text-xs text-slate-500 mb-8 font-semibold">El turno cerró con éxito. Extraiga el Reporte Z.</p>

                        <div class="flex gap-3">
                            <button @click="show = false" class="px-5 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cerrar</button>
                            <a href="{{ session('print_z_report_url') }}" target="_blank" @click="show = false" class="flex-1 py-3 px-2 text-sm font-bold text-white bg-slate-800 hover:bg-slate-900 shadow-lg shadow-slate-900/30 rounded-xl flex items-center justify-center gap-2 transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Reporte Z
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function parkingOperations() {
            return {
                // === ESTADOS GENERALES ===
                searchQuery: '',
                showDispensadora: false,
                showValidadora: false,
                selectedSpace: '',
                selectedSpaceId: null,

                showOpenShiftModal: false,
                showCloseShiftModal: false,

                // === ESTADOS DISPENSADORA ===
                searching: false,

                // Buscar cliente en BD al tipear la cédula
                searchClientData() {
                    // Evitamos buscar si la cédula es muy corta o está vacía
                    if (this.form.document.length < 4) return;

                    this.searchingClient = true;

                    fetch(`/clientes/buscar-doc/${this.form.document}`)
                        .then(response => response.json())
                        .then(data => {
                            this.searchingClient = false;

                            if (data.success) {
                                // Si existe, llenamos los campos automáticamente
                                this.form.name = data.name;
                                this.form.phone = data.phone || '';
                            }
                        })
                        .catch(error => {
                            this.searchingClient = false;
                            console.error('Error al buscar cliente:', error);
                        });
                },

                clientFound: false,
                plateProcessed: false,
                cameraOpen: false,
                stream: null,
                form: {
                    plate: '',
                    name: '',
                    document: '',
                    phone: ''
                },

                // === ESTADOS VALIDADORA ===
                loadingCheckout: false,
                checkoutData: {
                    ticket_id: null,
                    client_name: '',
                    client_document: '', // <-- NUEVO
                    client_phone: '', // <-- NUEVO
                    plate: '',
                    entry_time: '',
                    duration: '',
                    total_amount: 0
                },
                payments: [],

                get remainingBalance() {
                    const totalPagado = this.payments.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
                    return (this.checkoutData.total_amount - totalPagado).toFixed(2);
                },

                // ==========================================
                // MÉTODOS DE LA DISPENSADORA
                // ==========================================
                openDispensadora(spaceNumber, spaceId) {
                    this.form = { plate: '', name: '', document: '', phone: '' };
                    this.clientFound = false;
                    this.plateProcessed = false;
                    this.selectedSpace = spaceNumber;
                    this.selectedSpaceId = spaceId;
                    this.showDispensadora = true;
                },

                closeDispensadora() {
                    this.stopCamera();
                    this.showDispensadora = false;
                },

                searchClient() {
                    if(this.form.plate.trim() === '') return;

                    this.searching = true;
                    this.clientFound = false;
                    this.plateProcessed = false;

                    fetch(`/clientes/buscar/${this.form.plate.toUpperCase()}`)
                        .then(response => response.json())
                        .then(data => {
                            this.searching = false;
                            if(data && Object.keys(data).length > 0) {
                                this.form.name = data.name;
                                this.form.document = data.document;
                                this.form.phone = data.phone;
                                this.clientFound = true;
                            } else {
                                this.form.name = '';
                                this.form.document = '';
                                this.form.phone = '';
                            }
                            this.plateProcessed = true;
                        })
                        .catch(error => {
                            this.searching = false;
                            console.error('Error en búsqueda:', error);
                        });
                },

                startCamera() {
                    this.cameraOpen = true;
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                        .then(stream => {
                            this.stream = stream;
                            this.$refs.videoElement.srcObject = stream;
                        })
                        .catch(err => {
                            console.error("Error de cámara: ", err);
                            alert("Revisa los permisos de cámara en tu navegador.");
                            this.cameraOpen = false;
                        });
                },

                stopCamera() {
                    if(this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                        this.stream = null;
                    }
                    this.cameraOpen = false;
                },

                captureAndScan() {
                    if (!this.$refs.videoElement || !this.stream) return;

                    // Ponemos el botón en estado de "Buscando..."
                    this.searching = true;

                    const video = this.$refs.videoElement;

                    // 1. Creamos un canvas temporal para tomar la "foto" del video
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // 2. Convertimos el canvas a un archivo Blob (Imagen JPEG)
                    canvas.toBlob((blob) => {

                        // Apagamos la cámara apenas se tome la foto
                        this.stopCamera();

                        // 3. Preparamos el formulario para enviarlo a Laravel
                        const formData = new FormData();
                        formData.append('image', blob, 'placa_capturada.jpg');

                        // Añadimos el token CSRF de Laravel
                        const csrfToken = document.querySelector('input[name="_token"]').value;
                        formData.append('_token', csrfToken);

                        // 4. Enviamos la foto al puente de Laravel
                        fetch('{{ route("tickets.scan") }}', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    // Rellenamos el input con la placa limpiando espacios
                                    this.form.plate = data.plate.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();

                                    // Disparamos automáticamente la búsqueda del cliente
                                    this.searchClient();
                                } else {
                                    alert(data.error);
                                    this.searching = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error de red:', error);
                                alert('Error de comunicación con el sistema de Visión Artificial.');
                                this.searching = false;
                            });

                    }, 'image/jpeg', 0.9); // Calidad del 90%
                },

                // ==========================================
                // MÉTODOS DE LA VALIDADORA
                // ==========================================
                openValidadora(spaceNumber, spaceId) {
                    this.selectedSpace = spaceNumber;
                    this.selectedSpaceId = spaceId;
                    this.showValidadora = true;
                    this.loadingCheckout = true;
                    this.payments = [];

                    fetch(`/tickets/calcular/${spaceId}`)
                        .then(response => response.json())
                        .then(data => {
                            this.loadingCheckout = false;
                            if(data.error) {
                                alert(data.error);
                                this.showValidadora = false;
                            } else {
                                this.checkoutData = data;
                                this.payments = [{ method: 'Efectivo', amount: data.total_amount }];
                            }
                        })
                        .catch(err => {
                            this.loadingCheckout = false;
                            console.error('Error calculando:', err);
                        });
                },

                closeValidadora() {
                    this.showValidadora = false;
                },

                addPayment() {
                    let suggestedAmount = this.remainingBalance > 0 ? this.remainingBalance : 0;
                    this.payments.push({ method: 'Tarjeta', amount: suggestedAmount });
                },

                removePayment(index) {
                    this.payments.splice(index, 1);
                },

                // Envía el formulario oculto para anular
                cancelTicket() {
                    if(confirm('¿Estás seguro de anular este ticket? Esta acción no genera cobro y liberará el puesto.')) {
                        document.getElementById('cancelForm').submit();
                    }
                }
            }
        }
    </script>
@endsection
