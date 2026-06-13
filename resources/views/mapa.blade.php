@extends('layouts.public')
@section('title', 'Disponibilidad en Tiempo Real')

@section('content')
    <div class="min-h-[calc(100vh-6rem)] py-16 px-4 sm:px-6 lg:px-8 relative overflow-hidden bg-slate-50/50">

        <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-400/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-blue-400/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto relative z-10">

            <div class="text-center max-w-2xl mx-auto mb-12">
                <h1 class="font-poppins text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight">
                    Disponibilidad en <span class="text-transparent bg-clip-text bg-gradient-to-r from-qp-blue to-qp-indigo">Tiempo Real</span>
                </h1>
                <p class="text-slate-500 font-medium tracking-tight">Revisa el estado de nuestros puestos antes de ingresar al recinto. El mapa operativo se actualiza automáticamente.</p>
            </div>

            <div class="flex flex-wrap justify-center gap-4 mb-14">
                <div class="glass px-8 py-4 rounded-2xl shadow-lg shadow-slate-200/60 border border-white text-center min-w-[140px]">
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Capacidad Total</span>
                    <span class="block text-3xl font-poppins font-black text-slate-800">{{ $total }}</span>
                </div>
                <div class="bg-green-50/90 backdrop-blur-md border border-green-100/80 px-8 py-4 rounded-2xl shadow-lg shadow-green-100/50 text-center min-w-[140px]">
                    <span class="block text-xs font-bold text-green-500 uppercase tracking-wider mb-1">Puestos Libres</span>
                    <span class="block text-3xl font-poppins font-black text-green-600">{{ $available }}</span>
                </div>
                <div class="bg-slate-900/95 backdrop-blur-md border border-slate-800 px-8 py-4 rounded-2xl shadow-xl shadow-slate-950/20 text-center min-w-[140px]">
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ocupados</span>
                    <span class="block text-3xl font-poppins font-black text-white">{{ $occupied }}</span>
                </div>
            </div>

            <div class="bg-white/40 backdrop-blur-2xl p-8 sm:p-12 rounded-[3rem] shadow-[0_20px_50px_rgba(15,23,42,0.05)] border border-white/80 mb-10 relative overflow-hidden">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-32 bg-gradient-to-b from-indigo-500/10 to-transparent blur-3xl pointer-events-none"></div>

                <div class="text-center mb-10 relative z-10">
                    <h2 class="font-poppins text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">Distribución de Puestos</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">Los números verdes indican espacios listos para su uso</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-12 relative z-10">

                    @foreach($spaces as $space)
                        <div class="relative flex flex-col items-center group perspective-1000">

                            <div class="w-2/3 h-3.5 bg-gradient-to-b from-slate-200 to-slate-300 rounded-t-lg shadow-[inset_0_-2px_4px_rgba(0,0,0,0.1)] mb-1.5 z-0"></div>

                            @if($space->status == 'DISPONIBLE')
                                <div class="relative w-full aspect-[3/4] bg-gradient-to-b from-green-50/40 to-white/80 backdrop-blur-md border-x-4 border-dashed border-green-300/80 rounded-b-2xl flex flex-col items-center justify-center transition-all duration-500 shadow-[inset_0_-20px_30px_rgba(255,255,255,0.9)] hover:shadow-[0_15px_30px_rgba(34,197,94,0.15)] hover:-translate-y-1">

                                    <span class="font-poppins font-black text-slate-300 text-6xl group-hover:text-green-500 transition-colors duration-300 select-none drop-shadow-sm">{{ $space->space_number }}</span>

                                    <div class="absolute bottom-5 flex flex-col items-center">
                                        <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(74,222,128,0.8)]"></div>
                                    </div>

                                    <div class="absolute bottom-0 w-full h-1 bg-gradient-to-t from-green-500 to-transparent opacity-0 group-hover:opacity-60 transition-opacity duration-500"></div>
                                </div>
                            @else
                                <div class="relative w-full aspect-[3/4] bg-gradient-to-b from-slate-200/60 to-slate-100/40 backdrop-blur-md border-x-4 border-solid border-slate-300/80 rounded-b-2xl flex flex-col items-center justify-center transition-all duration-500 shadow-inner hover:shadow-[0_20px_40px_rgba(148,163,184,0.08)] hover:-translate-y-1">
                                    <span class="absolute font-poppins font-black text-slate-300/40 text-6xl select-none z-0">{{ $space->space_number }}</span>

                                    <img src="{{ asset('img/vehicle.png') }}" class="absolute z-10 w-24 drop-shadow-[0_25px_20px_rgba(0,0,0,0.35)] transform transition-all duration-700 group-hover:scale-105 group-hover:-translate-y-2" alt="Vehículo">

                                    <div class="absolute bottom-0 w-full h-1 bg-gradient-to-t from-slate-400 to-transparent opacity-0 group-hover:opacity-30 transition-opacity duration-500 z-10"></div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>

    <script>
        // Refresco de pantalla automatizado cada 30 segundos para mantener sincronía
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
@endsection
