@extends('layouts.public')
@section('title', 'Sobre Nosotros')

@section('content')
    <div class="min-h-[calc(100vh-6rem)] relative overflow-hidden bg-slate-50/50 py-20 px-4 sm:px-6 lg:px-8">

        <div class="absolute top-10 left-1/3 w-96 h-96 bg-qp-blue/10 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob"></div>
        <div class="absolute bottom-10 right-1/4 w-96 h-96 bg-qp-indigo/10 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob animation-delay-4000"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">

                <div class="grid grid-cols-12 gap-4 relative group">
                    <div class="col-span-12 transform transition-all duration-700 hover:scale-[1.01]">
                        <img src="{{ asset('img/desarrolladores.jpg') }}"
                             class="rounded-[2.5rem] shadow-2xl shadow-indigo-950/10 border border-white/60 object-cover w-full h-[300px]"
                             alt="Equipo QuickPark">
                    </div>
                    <div class="col-start-4 col-span-9 -mt-24 relative z-20 transform transition-all duration-700 hover:scale-[1.03]">
                        <img src="{{ asset('img/moto-carro.jpg') }}"
                             class="rounded-[2.5rem] border-[12px] border-slate-50 shadow-2xl shadow-indigo-950/15 object-cover w-full h-[220px]"
                             alt="Instalaciones de Parking">
                    </div>
                </div>

                <div class="space-y-10">
                    <header class="space-y-4">
                        <span class="text-xs font-bold text-qp-indigo uppercase tracking-widest bg-indigo-50 px-4 py-1.5 rounded-full inline-block">Nuestra Misión</span>
                        <h2 class="font-poppins text-4xl sm:text-5xl font-black text-slate-900 leading-tight tracking-tight">
                            Expertos en <span class="text-transparent bg-clip-text bg-gradient-to-r from-qp-blue to-qp-indigo">movilidad</span> urbana.
                        </h2>
                    </header>

                    <p class="text-lg sm:text-xl text-slate-500 leading-relaxed font-medium tracking-tight">
                        En QuickPark transformamos la experiencia de estacionamiento a través de tecnología de punta y un servicio impecable, reduciendo fricciones operativas y optimizando cada puesto.
                    </p>

                    <div class="grid sm:grid-cols-2 gap-6 pt-4">

                        <div class="glass p-6 rounded-3xl hover:-translate-y-2 transition-all duration-300 border border-white/60 shadow-lg shadow-slate-200/50 group">
                            <div class="w-12 h-12 bg-indigo-100 text-qp-indigo rounded-2xl flex items-center justify-center mb-4 font-bold text-lg group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 class="font-poppins font-bold text-lg text-slate-900 mb-1">Seguridad 24/7</h3>
                            <p class="text-sm text-slate-500 leading-relaxed font-medium">Monitoreo constante e ingreso controlado mediante IA para tu total tranquilidad.</p>
                        </div>

                        <div class="glass p-6 rounded-3xl hover:-translate-y-2 transition-all duration-300 border border-white/60 shadow-lg shadow-slate-200/50 group">
                            <div class="w-12 h-12 bg-blue-100 text-qp-blue rounded-2xl flex items-center justify-center mb-4 font-bold text-lg group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="font-poppins font-bold text-lg text-slate-900 mb-1">Tarifas Flexibles</h3>
                            <p class="text-sm text-slate-500 leading-relaxed font-medium">Algoritmos de cobro fraccionado que se adaptan al tiempo de uso real.</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
