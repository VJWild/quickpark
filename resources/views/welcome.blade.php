@extends('layouts.public')
@section('title', 'Bienvenido a QuickPark')

@section('content')
    <div class="min-h-[calc(100vh-6rem)] relative overflow-hidden bg-slate-50/50 pb-20">

        <div class="absolute top-0 left-1/4 w-[30rem] h-[30rem] bg-qp-blue/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-1/4 right-1/4 w-[30rem] h-[30rem] bg-qp-indigo/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-16">

            <div class="text-center max-w-3xl mx-auto mb-16">
                <h1 class="font-poppins text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight mb-6">
                    ¿En qué podemos <span class="text-transparent bg-clip-text bg-gradient-to-r from-qp-blue to-qp-indigo">ayudarte?</span>
                </h1>

                <div class="relative group mt-10">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-qp-indigo transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" class="w-full pl-16 pr-6 py-5 glass border border-white/80 rounded-full text-lg focus:ring-4 focus:ring-indigo-500/20 transition-all font-medium text-slate-700 placeholder-slate-400 shadow-xl shadow-indigo-900/5" placeholder="Busca tu placa, consulta tarifas o explora opciones...">
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6 mb-24">

                <a href="{{ route('mapa') }}" class="glass p-8 rounded-3xl hover:-translate-y-2 transition-all duration-300 border border-white/60 shadow-lg shadow-slate-200/50 group">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                    </div>
                    <h3 class="font-poppins font-bold text-xl text-slate-900 mb-2">Mapa en Vivo</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">Verifica los puestos disponibles y la ocupación en tiempo real antes de llegar.</p>
                </a>

                <a href="{{ route('sobre-nosotros') }}" class="glass p-8 rounded-3xl hover:-translate-y-2 transition-all duration-300 border border-white/60 shadow-lg shadow-slate-200/50 group">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-poppins font-bold text-xl text-slate-900 mb-2">Sobre QuickPark</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">Conoce nuestra tecnología de IA, nuestro equipo y cómo aseguramos tu vehículo 24/7.</p>
                </a>

                <a href="#acceso-operativo" class="glass p-8 rounded-3xl hover:-translate-y-2 transition-all duration-300 border border-white/60 shadow-lg shadow-slate-200/50 group">
                    <div class="w-14 h-14 bg-indigo-100 text-qp-indigo rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <h3 class="font-poppins font-bold text-xl text-slate-900 mb-2">Acceso Operativo</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">Ingresa al panel administrativo y financiero. Exclusivo para cajeros y supervisores.</p>
                </a>
            </div>

            <div id="acceso-operativo" class="max-w-md mx-auto pt-4 scroll-mt-32">
                <div class="glass p-10 sm:p-12 rounded-[2.5rem] shadow-2xl shadow-indigo-900/10 border border-white/60">

                    <div class="text-center mb-10">
                        <h2 class="font-poppins text-3xl font-bold text-slate-900 mb-3 tracking-tight">Iniciar Sesión</h2>
                        <p class="text-slate-500 font-medium tracking-tight">Ingresa tus credenciales para continuar</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50/80 backdrop-blur-sm border-l-4 border-red-500 rounded-r-xl">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium">{{ $errors->first() }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        <div class="space-y-2 relative">
                            <label for="email" class="block text-sm font-semibold text-slate-700 ml-1">Correo electrónico</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-qp-indigo transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                       class="w-full pl-11 pr-5 py-4 bg-white/40 border border-white/50 rounded-2xl focus:ring-2 focus:ring-qp-indigo focus:bg-white focus:border-transparent transition-all shadow-inner text-slate-800 placeholder-slate-400 font-medium"
                                       placeholder="ejemplo@quickpark.com">
                            </div>
                        </div>

                        <div class="space-y-2 relative">
                            <label for="password" class="block text-sm font-semibold text-slate-700 ml-1">Contraseña</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-qp-indigo transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </div>
                                <input type="password" name="password" id="password" required
                                       class="w-full pl-11 pr-12 py-4 bg-white/40 border border-white/50 rounded-2xl focus:ring-2 focus:ring-qp-indigo focus:bg-white focus:border-transparent transition-all shadow-inner text-slate-800 placeholder-slate-400 font-medium"
                                       placeholder="••••••••">

                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-qp-indigo focus:outline-none transition-colors">
                                    <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-qp-indigo focus:ring-qp-indigo border-slate-300 rounded bg-white/50 cursor-pointer">
                                <label for="remember_me" class="ml-2 block text-sm text-slate-600 font-medium cursor-pointer">
                                    Recordarme
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-semibold text-qp-indigo hover:text-indigo-800 transition-colors">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="w-full mt-6 py-4 bg-gradient-to-r from-qp-blue to-qp-indigo hover:from-indigo-600 hover:to-indigo-800 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:-translate-y-1 focus:ring-4 focus:ring-indigo-500/50">
                            Iniciar Sesión
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            if(type === 'password') {
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            } else {
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            }
        });

        // Smooth scroll para el botón de anclaje
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endsection
