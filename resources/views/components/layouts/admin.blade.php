<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Lectio Admin' }}</title>

    <link rel="icon" type="image/webp" href="{{ asset('img/logo.webp') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .brand-font { font-family: 'Playfair Display', serif; }
        body { font-family: 'Inter', sans-serif; }

        /* 2. REVISIÓN: Estilo de enlace activo mejorado para el hover */
        .active-link {
            background-color: #D4AF37 !important; /* Dorado Lectio */
            color: #000000 !important; /* Texto negro para contraste */
            font-weight: 700;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Evita que el texto desaparezca al pasar el ratón sobre el botón de la página actual */
        .active-link:hover {
            background-color: #B8962E !important; /* Un dorado un poco más oscuro al pasar el ratón */
            color: #000000 !important;
        }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

<aside class="w-64 bg-black text-white flex flex-col justify-between shadow-2xl z-50">
    <div>
        <div class="h-20 flex items-center px-6 border-b border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('img/logo.webp') }}" alt="Lectio Logo" class="h-10 w-auto group-hover:scale-110 transition-transform">
                <span class="text-2xl font-bold brand-font text-[#D4AF37] tracking-wider">Lectio</span>
            </a>
        </div>

        <nav class="p-4 space-y-2 mt-4">
            <p class="text-[10px] uppercase font-black text-gray-500 tracking-[0.2em] px-4 mb-4">{{ __('Navegación') }}</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-[#D4AF37] hover:bg-white/5 transition-all {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                <span class="text-sm uppercase tracking-widest font-bold">{{ __('Dashboard') }}</span>
            </a>

            <a href="{{ route('admin.inventory') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-[#D4AF37] hover:bg-white/5 transition-all {{ request()->routeIs('admin.inventory') ? 'active-link' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <span class="text-sm uppercase tracking-widest font-bold">{{ __('Inventario') }}</span>
            </a>

            <a href="{{ route('admin.orders') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-[#D4AF37] hover:bg-white/5 transition-all {{ request()->routeIs('admin.orders') ? 'active-link' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                <span class="text-sm uppercase tracking-widest font-bold">{{ __('Pedidos') }}</span>
            </a>

            <a href="{{ route('admin.coupons.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-[#D4AF37] hover:bg-white/5 transition-all {{ request()->routeIs('admin.coupons.*') ? 'active-link' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                <span class="text-sm uppercase tracking-widest font-bold">{{ __('Cupones') }}</span>
            </a>
            <a href="{{ route('admin.support.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-[#D4AF37] hover:bg-white/5 transition-all {{ request()->routeIs('admin.support.*') ? 'active-link' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span class="text-sm uppercase tracking-widest font-bold">{{ __('Soporte') }}</span>
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-gray-800 bg-zinc-900/50">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-xs text-gray-500 hover:text-[#D4AF37] transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            {{ __('Volver a la tienda') }}
        </a>
    </div>
</aside>

<main class="flex-1 flex flex-col min-w-0">
    <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-40">
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-300">{{ __('Admin') }}</span>
            <span class="text-gray-300">/</span>
            <span class="text-xs font-bold text-gray-800 uppercase tracking-widest">
                {{ str_replace('admin.', '', request()->route()->getName()) }}
            </span>
        </div>

        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3 border-l pl-6 border-gray-100">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-black text-black uppercase tracking-tighter">{{ Auth::user()->name }}</p>
                    <p class="text-[9px] text-[#D4AF37] font-bold uppercase tracking-widest">{{ __('Administrador') }}</p>
                </div>
                <div class="w-10 h-10 rounded-full border-2 border-[#D4AF37] p-0.5">
                    <img class="w-full h-full rounded-full object-cover"
                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=000&color=D4AF37"
                         alt="Admin">
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </button>
            </form>
        </div>
    </header>

    <div class="p-8 overflow-y-auto h-full custom-scrollbar bg-gray-50/50">
        {{ $slot }}
    </div>
</main>

</body>
</html>
