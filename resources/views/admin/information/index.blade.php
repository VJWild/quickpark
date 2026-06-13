@extends('layouts.admin')
@section('title', 'Información de la Empresa')

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
                <h1 class="font-poppins text-2xl font-bold text-slate-900">Perfil de la Empresa</h1>
                <p class="text-sm font-medium text-slate-500">Estos datos aparecerán impresos en los tickets y reportes</p>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar flex justify-center relative bg-slate-50/50">

            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-400/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 pointer-events-none"></div>

            <div class="w-full max-w-4xl bg-white/60 backdrop-blur-2xl rounded-[3rem] border border-white/80 shadow-[0_20px_50px_rgba(15,23,42,0.05)] p-10 sm:p-12 relative z-10 my-auto">

                <form method="POST" action="{{ route('information.store') }}" class="space-y-10">
                    @csrf

                    <div>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-qp-blue to-qp-indigo rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-poppins font-black text-slate-800 tracking-tight">Datos Comerciales</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Identidad principal del negocio</p>
                            </div>
                        </div>

                        <div class="bg-white/80 p-6 rounded-[2rem] border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nombre Comercial / Empresa <span class="text-red-500">*</span></label>
                                <input type="text" name="company_name" value="{{ $info->company_name ?? '' }}" required placeholder="Ej: QuickPark C.A." class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-qp-indigo transition-all font-bold text-slate-800 outline-none placeholder:font-normal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Actividad / Eslogan</label>
                                <input type="text" name="activity" value="{{ $info->activity ?? '' }}" placeholder="Ej: Servicio de Estacionamiento" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-qp-indigo transition-all font-semibold text-slate-700 outline-none placeholder:font-normal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Sucursal</label>
                                <input type="text" name="branch" value="{{ $info->branch ?? '' }}" placeholder="Ej: Sucursal No 1" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-qp-indigo transition-all font-semibold text-slate-700 outline-none placeholder:font-normal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Teléfono</label>
                                <input type="text" name="phone" value="{{ $info->phone ?? '' }}" placeholder="Ej: 0212-5555555" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-qp-indigo transition-all font-semibold text-slate-700 outline-none placeholder:font-normal">
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-green-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-500/30 shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-poppins font-black text-slate-800 tracking-tight">Ubicación Geográfica</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Domicilio fiscal y operativo</p>
                            </div>
                        </div>

                        <div class="bg-white/80 p-6 rounded-[2rem] border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Dirección Exacta</label>
                                <input type="text" name="address" value="{{ $info->address ?? '' }}" placeholder="Ej: Av. Principal, Edificio Torre Central, PB" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-qp-indigo transition-all font-semibold text-slate-700 outline-none placeholder:font-normal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Zona / Sector</label>
                                <input type="text" name="zone" value="{{ $info->zone ?? '' }}" placeholder="Ej: Centro Comercial" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-qp-indigo transition-all font-semibold text-slate-700 outline-none placeholder:font-normal">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Ciudad / Estado</label>
                                <input type="text" name="city" value="{{ $info->city ?? '' }}" placeholder="Ej: Macuto, La Guaira" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-qp-indigo transition-all font-semibold text-slate-700 outline-none placeholder:font-normal">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">País</label>
                                <input type="text" name="country" value="{{ $info->country ?? '' }}" placeholder="Ej: Venezuela" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-qp-indigo transition-all font-semibold text-slate-700 outline-none placeholder:font-normal">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end">
                        <button type="submit" class="group w-full py-4 text-sm font-black text-white bg-gradient-to-r from-qp-blue to-qp-indigo rounded-2xl shadow-[0_8px_15px_rgba(79,70,229,0.2)] transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-[1.02] hover:shadow-[0_12px_25px_rgba(79,70,229,0.4)] uppercase tracking-widest flex items-center justify-center gap-2 focus:outline-none">

                            <svg class="w-5 h-5 transition-transform duration-500 ease-in-out group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Guardar Información
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
