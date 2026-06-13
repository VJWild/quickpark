@extends('layouts.admin')
@section('title', 'Configuración de Tarifas')

@section('admin_content')
    <div class="flex flex-col h-full relative">

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
                <h1 class="font-poppins text-2xl font-bold text-slate-900">Tarifas del Sistema</h1>
                <p class="text-sm font-medium text-slate-500">Configura la modalidad y costos. Los cambios se aplicarán inmediatamente.</p>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar flex items-center justify-center relative bg-slate-50/50"
             x-data="{ mode: '{{ intval($prices->where('detail', 'MODO')->first()->amount ?? 1) }}' }">

            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-400/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>

            <div class="w-full max-w-4xl bg-white/60 backdrop-blur-2xl rounded-[3rem] border border-white/80 shadow-[0_20px_50px_rgba(15,23,42,0.05)] p-10 sm:p-12 relative z-10">

                <div class="flex items-center gap-5 mb-10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-qp-blue to-qp-indigo text-white flex items-center justify-center shadow-lg shadow-indigo-500/30 shrink-0 transform -rotate-3">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-poppins font-black text-slate-800 tracking-tight">Tarifador Automático</h2>
                        <p class="text-slate-500 font-medium text-sm mt-1">Selecciona cómo deseas cobrar a tus clientes.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('prices.store') }}" class="space-y-10">
                    @csrf

                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-2">1. Modalidad de Cobro</label>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="mode" value="1" x-model="mode" class="peer sr-only">
                                <div class="p-6 rounded-3xl border-2 transition-all duration-300"
                                     :class="mode == '1' ? 'border-qp-indigo bg-indigo-50/50 shadow-lg shadow-indigo-500/10' : 'border-slate-200 bg-white hover:border-indigo-300 hover:bg-slate-50'">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-bold text-slate-800" :class="mode == '1' && 'text-qp-indigo'">Sistema Mixto</h3>
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors" :class="mode == '1' ? 'border-qp-indigo' : 'border-slate-300'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-qp-indigo scale-0 transition-transform" :class="mode == '1' && 'scale-100'"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium">Cobra el día base y añade fracciones de horas sueltas.</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="mode" value="2" x-model="mode" class="peer sr-only">
                                <div class="p-6 rounded-3xl border-2 transition-all duration-300"
                                     :class="mode == '2' ? 'border-qp-indigo bg-indigo-50/50 shadow-lg shadow-indigo-500/10' : 'border-slate-200 bg-white hover:border-indigo-300 hover:bg-slate-50'">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-bold text-slate-800" :class="mode == '2' && 'text-qp-indigo'">Tarifa Plana</h3>
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors" :class="mode == '2' ? 'border-qp-indigo' : 'border-slate-300'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-qp-indigo scale-0 transition-transform" :class="mode == '2' && 'scale-100'"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium">Cobro único de 24 horas sin importar fracciones de tiempo.</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="mode" value="3" x-model="mode" class="peer sr-only">
                                <div class="p-6 rounded-3xl border-2 transition-all duration-300"
                                     :class="mode == '3' ? 'border-qp-indigo bg-indigo-50/50 shadow-lg shadow-indigo-500/10' : 'border-slate-200 bg-white hover:border-indigo-300 hover:bg-slate-50'">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-bold text-slate-800" :class="mode == '3' && 'text-qp-indigo'">Por Hora (Estricto)</h3>
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors" :class="mode == '3' ? 'border-qp-indigo' : 'border-slate-300'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-qp-indigo scale-0 transition-transform" :class="mode == '3' && 'scale-100'"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium">Ignora los días. Multiplica el total de horas corridas.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-slate-200/50">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-2">2. Asignación de Costos</label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            <div x-show="mode != '3'" x-transition
                                 class="bg-white/80 p-6 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden group">
                                <div class="absolute left-0 top-0 w-1.5 h-full bg-gradient-to-b from-indigo-500 to-blue-500"></div>
                                <label class="block text-xs font-bold text-indigo-800 uppercase tracking-widest mb-3 ml-2">Precio por DÍA (24h)</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-black text-2xl">$</span>
                                    <input type="number" step="0.01" name="price_day" value="{{ $prices->where('detail', 'DIAS')->first()->amount ?? '3.00' }}" required
                                           class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all font-poppins font-black text-3xl text-slate-800 outline-none">
                                </div>
                            </div>

                            <div x-show="mode != '2'" x-transition
                                 class="bg-white/80 p-6 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden group">
                                <div class="absolute left-0 top-0 w-1.5 h-full bg-gradient-to-b from-blue-400 to-cyan-500"></div>
                                <label class="block text-xs font-bold text-blue-800 uppercase tracking-widest mb-3 ml-2">Precio por HORA</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-black text-2xl">$</span>
                                    <input type="number" step="0.01" name="price_hour" value="{{ $prices->where('detail', 'HORAS')->first()->amount ?? '0.50' }}" required
                                           class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/20 focus:border-blue-400 transition-all font-poppins font-black text-3xl text-slate-800 outline-none">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="pt-8 flex justify-end">
                        <button type="submit" class="px-10 py-4 text-sm font-bold text-white bg-slate-900 hover:bg-black rounded-2xl shadow-xl shadow-slate-900/20 transition-all flex items-center gap-2 hover:-translate-y-1 uppercase tracking-widest">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Guardar Tarifas
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
