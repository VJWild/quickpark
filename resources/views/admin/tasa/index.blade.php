@extends('layouts.admin')
@section('title', 'Tasa de Cambio del Día')

@section('admin_content')
    <div class="flex flex-col h-full relative">

        @if(session('success'))
            <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-slate-900/95 backdrop-blur-xl border border-slate-700 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3"
                 x-data="{ show: true }" x-show="show"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-4"
                 x-init="setTimeout(() => show = false, 4000)">
                <div class="w-8 h-8 bg-green-500/20 text-green-400 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="font-bold text-sm tracking-wide">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-red-900/95 backdrop-blur-xl border border-red-700 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3"
                 x-data="{ show: true }" x-show="show"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-4"
                 x-init="setTimeout(() => show = false, 5000)">
                <div class="w-8 h-8 bg-red-500/20 text-red-400 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="font-bold text-sm tracking-wide">{{ $errors->first() }}</span>
            </div>
        @endif
        <header class="h-24 glass border-b border-white/50 px-8 flex items-center justify-between flex-shrink-0 z-10">
            <div>
                <h1 class="font-poppins text-2xl font-bold text-slate-900">Control de Divisas</h1>
                <p class="text-sm font-medium text-slate-500">Establece la tasa oficial de conversión para los cobros y reportes</p>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar flex items-center justify-center relative bg-slate-50/50">

            <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-400/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 pointer-events-none"></div>

            <div class="w-full max-w-md bg-white/60 backdrop-blur-2xl rounded-[3rem] border border-white/80 shadow-[0_20px_50px_rgba(15,23,42,0.05)] p-10 sm:p-12 relative z-10 text-center">

                <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-green-500/30 text-white transform rotate-3">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>

                <h3 class="text-2xl font-poppins font-black text-slate-800 mb-1 tracking-tight">Tasa BCV</h3>
                <p class="text-[10px] font-bold text-slate-400 mb-8 uppercase tracking-widest">Valor actual del Dólar en Bs.</p>

                <form method="POST" action="{{ route('tasa.update') }}" class="space-y-8">
                    @csrf

                    <div class="bg-white/80 p-6 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden group">
                        <div class="absolute left-0 top-0 w-1.5 h-full bg-gradient-to-b from-emerald-400 to-green-500"></div>
                        <div class="relative flex items-center justify-center gap-2">
                            <span class="font-poppins font-bold text-slate-400 text-2xl mt-1">Bs.</span>
                            <input type="number" step="0.01" min="0.01" name="rate" value="{{ $tasa->rate ?? '1.00' }}" required
                                   class="w-full max-w-[200px] bg-transparent border-none p-0 focus:ring-0 font-poppins font-black text-5xl text-center text-slate-800 outline-none">
                        </div>
                    </div>

                    <button type="submit" class="group w-full py-4 text-sm font-black text-white bg-gradient-to-r from-qp-blue to-qp-indigo rounded-2xl shadow-[0_8px_15px_rgba(79,70,229,0.2)] transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-[0_12px_25px_rgba(79,70,229,0.4)] uppercase tracking-widest flex items-center justify-center gap-2 focus:outline-none">

                        <svg class="w-5 h-5 transition-transform duration-500 ease-in-out group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>

                        <span>Actualizar Tasa</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
