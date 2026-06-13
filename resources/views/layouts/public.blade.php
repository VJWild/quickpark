<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QuickPark - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900 selection:bg-indigo-100 selection:text-indigo-700 flex flex-col min-h-screen">

<nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-lg border-b border-white/50 shadow-sm transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="flex-shrink-0 flex items-center h-full">
                <a href="{{ route('home') }}" class="block transition-transform duration-300 hover:scale-[1.02] relative flex items-center h-full py-2">
                    <img src="{{ asset('img/minimalist--futuristic--quickpark-letter.svg.png') }}"
                         alt="QuickPark Logo"
                         class="w-auto max-w-[180px] sm:max-w-[220px] object-contain drop-shadow-lg scale-110 origin-left">
                </a>
            </div>

            <div class="hidden md:flex space-x-8">
                @php
                    // Clases base: Posicionamiento relativo y preparación de la animación de la línea inferior
                    $baseNav = "relative font-semibold text-sm tracking-wide transition-colors py-2 after:content-[''] after:absolute after:bottom-0 after:h-[2px] after:bg-qp-indigo after:transition-all after:duration-300 after:ease-in-out";

                    // Si es la vista actual: La línea ocupa el 100% y el texto es azul/índigo
                    $activeNav = "text-qp-indigo after:w-full after:left-0";

                    // Si NO es la vista actual: La línea está oculta en el centro (width 0) y se expande al hacer hover
                    $inactiveNav = "text-gray-600 hover:text-qp-indigo after:w-0 hover:after:w-full after:left-1/2 hover:after:left-0";
                @endphp

                <a href="{{ route('home') }}" class="{{ $baseNav }} {{ request()->routeIs('home') ? $activeNav : $inactiveNav }}">
                    INICIO
                </a>
                <a href="{{ route('mapa') }}" class="{{ $baseNav }} {{ request()->routeIs('mapa') ? $activeNav : $inactiveNav }}">
                    MAPA DE PUESTOS
                </a>
                <a href="{{ route('sobre-nosotros') }}" class="{{ $baseNav }} {{ request()->routeIs('sobre-nosotros') ? $activeNav : $inactiveNav }}">
                    SOBRE NOSOTROS
                </a>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-gray-600 hover:text-qp-indigo focus:outline-none transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<div id="mobile-menu" class="hidden md:hidden bg-white/70 backdrop-blur-lg border-b border-white/50 pb-4">
    @php
        $baseMobile = "block px-6 py-3 font-semibold transition-colors border-l-4";
        $activeMobile = "text-qp-indigo bg-white/50 border-qp-indigo";
        $inactiveMobile = "text-gray-600 hover:bg-white/50 hover:text-qp-indigo border-transparent";
    @endphp

    <a href="{{ route('home') }}" class="{{ $baseMobile }} {{ request()->routeIs('home') ? $activeMobile : $inactiveMobile }}">
        INICIO
    </a>
    <a href="{{ route('mapa') }}" class="{{ $baseMobile }} {{ request()->routeIs('mapa') ? $activeMobile : $inactiveMobile }}">
        MAPA DE PUESTOS
    </a>
    <a href="{{ route('sobre-nosotros') }}" class="{{ $baseMobile }} {{ request()->routeIs('sobre-nosotros') ? $activeMobile : $inactiveMobile }}">
        SOBRE NOSOTROS
    </a>
</div>

<main class="flex-grow">
    @yield('content')
</main>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
</body>
</html>
