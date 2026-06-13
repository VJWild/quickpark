@extends('layouts.admin')
@section('title', 'Auditoría de Turnos')

@section('admin_content')
    <div x-data="shiftManager()" class="flex flex-col h-full relative">

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

        <header class="h-24 glass border-b border-white/50 px-8 flex items-center justify-between flex-shrink-0 z-10">
            <div>
                <h1 class="font-poppins text-2xl font-bold text-slate-900">Auditoría de Cajas</h1>
                <p class="text-sm font-medium text-slate-500">Historial de turnos, arqueos y reportes</p>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-slate-50/50 relative">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-400/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 pointer-events-none"></div>

            <div class="max-w-7xl mx-auto space-y-8 relative z-10">

                <div class="flex flex-col sm:flex-row justify-between items-end gap-6">

                    <div class="glass px-6 py-4 rounded-2xl flex items-center gap-4 shadow-[0_8px_30px_rgba(15,23,42,0.04)] border border-white/80 w-full sm:w-auto">
                        <div class="w-12 h-12 bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl flex items-center justify-center text-white shadow-lg shadow-slate-900/30 shrink-0 transform rotate-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Turnos Totales</span>
                            <span class="block text-2xl font-poppins font-black text-slate-800 leading-none mt-1">{{ $shifts->count() }}</span>
                        </div>
                    </div>

                    <div class="relative w-full sm:w-96 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-qp-indigo transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" x-model="search" placeholder="Buscar por ID o nombre de cajero..."
                               class="w-full pl-12 pr-10 py-4 bg-white/60 backdrop-blur-md border border-white/80 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-qp-indigo transition-all font-semibold text-slate-700 shadow-[0_8px_30px_rgba(15,23,42,0.04)] placeholder-slate-400 outline-none">

                        <button type="button" x-show="search !== ''" @click="search = ''" style="display: none;" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-red-500 transition-colors focus:outline-none">
                            <svg class="h-5 w-5 bg-slate-100 rounded-full p-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div class="bg-white/20 backdrop-blur-xl rounded-[2.5rem] border border-white/60 shadow-[0_20px_50px_rgba(15,23,42,0.04)] p-6 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                            <tr class="text-slate-400">
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest pl-6">ID Turno</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cajero</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Apertura / Cierre</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Estado</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Sis. Ventas</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Diferencia</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right pr-6">Reportes</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($shifts as $shift)
                                <tr x-show="matchesSearch('{{ $shift->id }}', '{{ $shift->user->name }}')"
                                    x-transition.opacity.duration.300ms
                                    class="group bg-white/40 backdrop-blur-md hover:bg-white hover:shadow-[0_12px_30px_rgba(79,70,229,0.08)] hover:-translate-y-1 transition-all duration-300 transform">

                                    <td class="p-4 pl-6 rounded-l-2xl transition-colors duration-300">
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-black bg-slate-800 text-white tracking-widest shadow-sm border border-slate-700">
                                                #{{ $shift->id }}
                                            </span>
                                    </td>

                                    <td class="p-4 transition-colors duration-300">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-700 font-bold flex items-center justify-center text-xs shadow-sm border border-indigo-300/50">
                                                {{ substr($shift->user->name, 0, 1) }}
                                            </div>
                                            <span class="font-bold text-slate-800">{{ $shift->user->name }}</span>
                                        </div>
                                    </td>

                                    <td class="p-4 text-xs font-semibold text-slate-600 transition-colors duration-300">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <div class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_5px_rgba(34,197,94,0.6)]"></div>
                                            {{ \Carbon\Carbon::parse($shift->start_time)->format('d/m/Y h:i A') }}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full {{ $shift->status == 'ABIERTO' ? 'bg-slate-300' : 'bg-red-500 shadow-[0_0_5px_rgba(239,68,68,0.6)]' }}"></div>
                                            {{ $shift->end_time ? \Carbon\Carbon::parse($shift->end_time)->format('d/m/Y h:i A') : 'En Progreso...' }}
                                        </div>
                                    </td>

                                    <td class="p-4 transition-colors duration-300">
                                        @if($shift->status == 'ABIERTO')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 border border-green-200 text-green-700 text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> En Curso
                                                </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 border border-slate-200 text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                                    Cerrado
                                                </span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-right transition-colors duration-300">
                                            <span class="font-poppins font-bold text-slate-700">
                                                ${{ number_format($shift->system_amount, 2) }}
                                            </span>
                                    </td>

                                    <td class="p-4 text-right transition-colors duration-300">
                                        @if($shift->status == 'CERRADO')
                                            <span class="font-poppins font-black px-3 py-1 rounded-lg border {{ $shift->difference < 0 ? 'bg-red-50 border-red-100 text-red-600' : ($shift->difference > 0 ? 'bg-amber-50 border-amber-100 text-amber-600' : 'bg-emerald-50 border-emerald-100 text-emerald-600') }}">
                                                    {{ $shift->difference > 0 ? '+' : '' }}${{ number_format($shift->difference, 2) }}
                                                </span>
                                        @else
                                            <span class="text-slate-300 font-bold">--</span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-right pr-6 rounded-r-2xl transition-colors duration-300">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($shift->status == 'CERRADO')
                                                <a href="{{ route('shifts.printSales', $shift->id) }}" target="_blank" title="Resumen de Ventas" class="p-2.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white rounded-xl transition-all shadow-sm focus:outline-none border border-transparent hover:shadow-lg hover:shadow-indigo-500/30">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                </a>
                                                <a href="{{ route('shifts.printZ', $shift->id) }}" target="_blank" title="Declaración de Caja" class="p-2.5 bg-slate-100 hover:bg-slate-800 text-slate-600 hover:text-white rounded-xl transition-all shadow-sm focus:outline-none border border-transparent hover:shadow-lg hover:shadow-slate-900/30">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                </a>
                                            @else
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                                                        Pendiente
                                                    </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-16 text-center bg-white/20 backdrop-blur-md rounded-3xl border border-dashed border-slate-200">
                                        <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <span class="font-bold text-lg text-slate-600">Aún no hay turnos</span>
                                            <span class="text-sm font-medium mt-1">No se ha registrado ninguna apertura de caja en el sistema.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            @if($shifts->count() > 0)
                                <tr x-show="!hasResults()" style="display: none;">
                                    <td colspan="7" class="p-16 text-center bg-white/20 backdrop-blur-md rounded-3xl border border-dashed border-slate-200 mt-4">
                                        <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span class="font-bold text-lg text-slate-600">No hay coincidencias</span>
                                            <span class="text-sm font-medium mt-1">Intenta con otro ID de turno o nombre de cajero.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function shiftManager() {
            return {
                search: '',

                matchesSearch(id, cajeroName) {
                    if (this.search.trim() === '') return true;
                    const term = this.search.toLowerCase();
                    return id.toString().includes(term) ||
                        cajeroName.toLowerCase().includes(term);
                },

                hasResults() {
                    if (this.search.trim() === '') return true;
                    const rows = document.querySelectorAll('tbody tr.group');
                    let found = false;
                    rows.forEach(row => {
                        if(row.style.display !== 'none') found = true;
                    });
                    return found;
                }
            }
        }
    </script>
@endsection
