@php
    $unreadSupport = 0;
    if(Auth::check()) {
        $unreadSupport = \App\Models\SupportMessage::where('user_id', Auth::id())
                            ->where('is_admin_reply', true)
                            ->where('user_read', false)
                            ->count();
    }
@endphp

<div class="navbar bg-white/95 backdrop-blur-sm sticky top-0 z-50 border-b border-gray-100 px-4 md:px-8 shadow-sm">

    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="{{ route('home') }}">{{__("Inicio")}}</a></li>
                <li><a href="{{ Route::has('catalogo') ? route('catalogo') : '#' }}">{{__("Catálogo")}}</a></li>
                <li>
                    <a href="{{ route('contacto') }}" class="justify-between">
                        {{__("Contacto")}}
                        @if($unreadSupport > 0)
                            <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full animate-pulse">{{ $unreadSupport }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        <a href="{{ route('home') }}" class="btn btn-ghost text-2xl font-bold brand-font text-brand-red hover:bg-transparent px-0 sm:px-2 flex items-center gap-2">
            <img src="{{ asset('img/logo.webp') }}" alt="Lectio Logo" class="h-10 w-auto object-contain">
            Lectio
        </a>
    </div>

    <div class="navbar-center hidden lg:flex items-center gap-6">
        <ul class="menu menu-horizontal px-1 gap-4 text-base font-medium text-gray-700">
            <li>
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'active !text-black !bg-transparent font-bold border-b-2 border-brand-red rounded-none pb-1' : 'hover:text-brand-red hover:bg-transparent transition' }}">
                    {{__("Inicio")}}
                </a>
            </li>
            <li>
                <a href="{{ Route::has('catalogo') ? route('catalogo') : '#' }}"
                   class="{{ request()->routeIs('catalogo') ? 'active !text-black !bg-transparent font-bold border-b-2 border-brand-red rounded-none pb-1' : 'hover:text-brand-red hover:bg-transparent transition' }}">
                    {{__("Catálogo")}}
                </a>
            </li>
            <li>
                <a href="{{ route('contacto') }}" class="relative hover:text-brand-red hover:bg-transparent transition">
                    {{__("Contacto")}}
                    @if($unreadSupport > 0)
                        <span class="absolute -top-2 -right-3 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full animate-bounce shadow-md">
                            {{ $unreadSupport }}
                        </span>
                    @endif
                </a>
            </li>
        </ul>

        {{-- NUEVO: BUSCADOR LIVE --}}
        <div class="relative hidden md:block w-64 group z-50">
            <div class="relative flex items-center">
                <svg class="w-4 h-4 absolute left-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" id="live-search-input" placeholder="Buscar libros, autores..."
                       class="w-full pl-9 pr-4 py-1.5 bg-gray-100 border-none rounded-full text-sm focus:ring-2 focus:ring-[#D4AF37] transition-all focus:bg-white focus:shadow-md">
            </div>
            <div id="search-results" class="absolute top-full mt-2 w-full bg-white border border-gray-100 rounded-xl shadow-xl hidden overflow-hidden z-50">
                <ul id="results-list" class="divide-y divide-gray-50 max-h-64 overflow-y-auto"></ul>
            </div>
        </div>
    </div>

    <div class="navbar-end gap-1 sm:gap-2">

        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn bg-gray-900 btn-sm w-1/4 text-white hover:bg-gray-700 border-none normal-case font-medium">
                    Panel Admin
                </a>
            @endif
        @endauth

        <div class="flex items-center gap-6">
            <a href="{{ route('cart.index') }}" id="cart-icon-container" class="relative text-gray-600 hover:text-gray-900 transition group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>

                {{-- La burbuja ahora siempre existe, pero se oculta si está a 0 --}}
                <span id="cart-counter-badge" class="absolute -top-2 -right-2 bg-gray-900 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-sm group-hover:bg-black transition {{ (session('cart') && count(session('cart')) > 0) ? '' : 'hidden' }}">
                    {{ session('cart') ? count(session('cart')) : 0 }}
                </span>
            </a>
        </div>

        @if (Route::has('login'))
            @auth
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar border border-gray-200 hover:border-brand-red transition">
                        <div class="w-10 rounded-full">
                            <img alt="Perfil" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=d64028&color=fff" />
                        </div>
                    </div>

                    <ul tabindex="0" class="mt-3 z-[1] p-2 shadow-lg menu menu-sm dropdown-content bg-white rounded-box w-52 border border-gray-100">
                        <li class="menu-title px-4 py-2 border-b border-gray-100 mb-2">
                            <span class="text-gray-900 font-bold block truncate">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-500 font-normal truncate">{{ Auth::user()->email }}</span>
                        </li>

                        {{-- NUEVO: MOSTRAR PUNTOS Y BOTÓN DE CANJE --}}
                        <li class="px-4 py-2 mb-1 bg-yellow-50/50 rounded-lg">
                            <div class="flex justify-between items-center w-full px-0">
                                <span class="text-xs font-bold text-[#D4AF37]">Puntos: {{ Auth::user()->points ?? 0 }}</span>
                                @if((Auth::user()->points ?? 0) >= 100)
                                    <form action="{{ route('points.redeem') }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="text-[9px] bg-black text-[#D4AF37] px-2 py-1 rounded hover:scale-105 transition">Canjear</button>
                                    </form>
                                @endif
                            </div>
                        </li>

                        <li>
                            <a href="{{ route('profile.edit') }}" class="py-2 text-gray-700 hover:text-brand-red hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{__("Mi cuenta")}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('library.index') }}" class="py-2 text-gray-700 hover:text-brand-red hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                {{__("Biblioteca personal")}}
                            </a>
                        </li>

                        {{-- NUEVO: ENLACE WISHLIST --}}
                        <li>
                            <a href="{{ route('wishlist.index') }}" class="py-2 text-gray-700 hover:text-brand-red hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                {{__("Lista de deseos")}}
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('orders.index') }}" class="py-2 text-gray-700 hover:text-brand-red hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                {{__("Historial de Pedidos")}}
                            </a>
                        </li>

                        <div class="divider my-1"></div>

                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 py-2 px-4 text-red-600 text-sm font-medium transition-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{__("Cerrar sesión")}}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            @else
                <div class="hidden md:flex items-center gap-2 border-l border-gray-200 pl-4">
                    <a href="{{ route('login') }}" class="btn btn-sm btn-ghost text-gray-700 font-medium hover:text-brand-red">{{__("Iniciar sesión")}}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-sm bg-gray-900 text-white hover:bg-gray-700 border-none rounded px-4">{{__("Registrarse")}}</a>
                    @endif
                </div>

                <a href="{{ route('login') }}" class="btn btn-ghost btn-circle btn-sm md:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </a>
            @endauth
        @endif
    </div>
</div>

{{-- SCRIPT PARA EL BUSCADOR EN VIVO --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById('live-search-input');
        const resultsBox = document.getElementById('search-results');
        const resultsList = document.getElementById('results-list');

        if(input) {
            input.addEventListener('input', function() {
                let query = this.value;

                if(query.length < 2) {
                    resultsBox.classList.add('hidden');
                    return;
                }

                fetch(`/api/search?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsList.innerHTML = '';
                        if(data.length > 0) {
                            data.forEach(book => {
                                resultsList.innerHTML += `
                                    <li>
                                        <a href="/libro/${book.id}" class="flex items-center gap-3 p-3 hover:bg-gray-50 transition">
                                            <img src="${book.image_url}" class="w-8 h-12 object-cover rounded shadow-sm">
                                            <div>
                                                <p class="text-xs font-bold text-gray-900 truncate">${book.title}</p>
                                                <p class="text-[10px] text-gray-500">${book.author}</p>
                                            </div>
                                        </a>
                                    </li>`;
                            });
                            resultsBox.classList.remove('hidden');
                        } else {
                            resultsList.innerHTML = `<li class="p-4 text-xs text-center text-gray-500">No hay resultados</li>`;
                            resultsBox.classList.remove('hidden');
                        }
                    });
            });

            document.addEventListener('click', function(e) {
                if(!input.contains(e.target) && !resultsBox.contains(e.target)) {
                    resultsBox.classList.add('hidden');
                }
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleccionamos todos los formularios de "Añadir"
        const ajaxCartForms = document.querySelectorAll('.ajax-cart-form');

        ajaxCartForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Solo detenemos la recarga si NO hay un input oculto con value="buy_now"
                const isBuyNow = this.querySelector('input[name="action"][value="buy_now"]');
                if (isBuyNow) return; // Si es comprar ahora, que recargue normal

                e.preventDefault(); // Evita que la página se recargue

                const url = this.action;
                const formData = new FormData(this);
                const btn = this.querySelector('button[type="submit"]');

                // Efecto visual de botón cargando
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="animate-pulse">⏳ Añadiendo...</span>';
                btn.disabled = true;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;

                        if (data.success) {
                            // Actualizar el numerito rojo del carrito
                            const cartBadge = document.getElementById('cart-counter-badge');
                            if (cartBadge) {
                                cartBadge.innerText = data.cartCount;
                                cartBadge.classList.remove('hidden');
                            }

                            // Mostrar aviso elegante (SweetAlert si lo tienes, o un alert normal)
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    toast: true, position: 'top-end', icon: 'success',
                                    title: data.message, showConfirmButton: false, timer: 2000
                                });
                            } else {
                                // Cambiar temporalmente el texto del botón a "Añadido!"
                                btn.innerHTML = '✅ ¡Añadido!';
                                setTimeout(() => { btn.innerHTML = originalText; }, 2000);
                            }
                        }
                    })
                    .catch(error => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        console.error('Error:', error);
                    });
            });
        });
    });
</script>
