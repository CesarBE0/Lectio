<x-layouts.layout title="{{ __('Configuración') }} - Lectio">

    <div class="bg-gray-50 min-h-screen py-12">
        <div class="container mx-auto px-4 md:px-6 max-w-4xl">

            @if(session('success'))
                <div class="mb-8 bg-black border border-[#D4AF37]/50 text-[#D4AF37] px-4 py-3 rounded-lg relative flex items-center gap-3 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="font-bold tracking-wide text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-10 border-b-2 border-[#D4AF37] pb-6">
                <h1 class="text-4xl font-serif font-bold text-black mb-2 uppercase tracking-widest">{{ __('Configuración') }}</h1>
                <p class="text-gray-500 font-medium">{{ __('Gestiona tu cuenta, dirección y preferencias exclusivas.') }}</p>
            </div>

            <div class="grid grid-cols-1 gap-8">

                <div class="card bg-white shadow-sm border border-gray-100 overflow-visible relative z-20 group hover:shadow-md transition-shadow duration-300">
                    <div class="card-body p-8">
                        <div class="flex items-start gap-5">
                            <div class="p-3 bg-black rounded shadow-sm border border-[#D4AF37]/30 text-[#D4AF37] group-hover:scale-105 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" /></svg>
                            </div>
                            <div class="w-full">
                                <h3 class="text-xl font-serif font-bold text-black mb-1">{{ __('Idioma de la Tienda') }}</h3>
                                <p class="text-gray-500 text-sm mb-5">{{ __('Selecciona en qué idioma prefieres disfrutar de nuestro catálogo.') }}</p>

                                <div class="relative w-full max-w-md" id="language-dropdown">
                                    <button onclick="toggleDropdown()" class="w-full flex items-center justify-between input input-bordered bg-gray-50 border-gray-200 text-left cursor-pointer focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all">
                                        <span id="current-lang-label" class="flex items-center gap-3">
                                            @php
                                                $currentLang = collect($languages)->firstWhere('code', app()->getLocale());
                                                $flagCode = $currentLang['flag_country'] ?? 'es';
                                                $langName = $currentLang['name'] ?? 'Español';
                                            @endphp

                                            <img src="https://flagcdn.com/w40/{{ $flagCode }}.png"
                                                 alt="{{ $langName }}"
                                                 class="w-6 h-auto shadow-sm rounded-sm border border-gray-200">

                                            <span class="font-bold text-gray-800">
                                                {{ $langName }}
                                            </span>
                                        </span>
                                        <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    <div id="dropdown-menu" class="hidden absolute mt-2 w-full bg-white border border-gray-100 rounded-lg shadow-2xl max-h-60 overflow-y-auto z-50">

                                        <div class="sticky top-0 bg-white p-3 border-b border-gray-100">
                                            <input type="text" id="lang-search" onkeyup="filterLanguages()" placeholder="{{ __('Buscar idioma...') }}" class="input input-sm input-bordered w-full bg-gray-50 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] focus:outline-none transition-all">
                                        </div>

                                        <ul id="lang-list" class="py-2">
                                            @foreach($languages as $lang)
                                                <li>
                                                    <a href="{{ route('lang.switch', $lang['code']) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-black hover:text-[#D4AF37] transition-colors cursor-pointer text-gray-700 lang-item group/item">

                                                        <img src="https://flagcdn.com/w40/{{ $lang['flag_country'] }}.png"
                                                             alt="{{ $lang['name'] }}"
                                                             class="w-6 h-auto shadow-sm rounded-sm">

                                                        <span class="font-medium lang-name group-hover/item:text-[#D4AF37] transition-colors">{{ $lang['name'] }}</span>

                                                        @if(app()->getLocale() == $lang['code'])
                                                            <svg class="w-5 h-5 text-[#D4AF37] ml-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf

                    <div class="card bg-white shadow-sm border border-gray-100 mb-8 group hover:shadow-md transition-shadow duration-300">
                        <div class="card-body p-8">
                            <h3 class="text-xl font-serif font-bold text-black mb-6 flex items-center gap-3 border-b border-gray-50 pb-4">
                                <span class="text-[#D4AF37]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </span>
                                {{ __('Datos Personales') }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ __('Nombre completo') }}</span></label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input input-bordered bg-gray-50 border-gray-200 focus:bg-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] focus:outline-none transition-all text-black font-medium" />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ __('Correo electrónico') }}</span></label>
                                    <input type="email" value="{{ $user->email }}" class="input input-bordered bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200" readonly title="El correo no se puede modificar por seguridad" />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ __('Teléfono') }}</span></label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+34 600..." class="input input-bordered bg-gray-50 border-gray-200 focus:bg-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] focus:outline-none transition-all text-black font-medium" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-white shadow-sm border border-gray-100 group hover:shadow-md transition-shadow duration-300">
                        <div class="card-body p-8">
                            <h3 class="text-xl font-serif font-bold text-black mb-2 flex items-center gap-3">
                                <span class="text-[#D4AF37]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </span>
                                {{ __('Dirección de Envío') }}
                            </h3>
                            <p class="text-gray-500 text-sm mb-6 border-b border-gray-50 pb-4">{{ __('Guarda tu dirección principal para agilizar el envío de tus futuras joyas literarias.') }}</p>

                            <div class="grid grid-cols-1 gap-6">
                                <div class="form-control">
                                    <label class="label"><span class="label-text text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ __('Dirección completa') }}</span></label>
                                    <input type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="Calle Ejemplo, 123, 2ºA" class="input input-bordered w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] focus:outline-none transition-all text-black font-medium" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="form-control">
                                        <label class="label"><span class="label-text text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ __('Ciudad') }}</span></label>
                                        <input type="text" name="city" value="{{ old('city', $user->city) }}" class="input input-bordered bg-gray-50 border-gray-200 focus:bg-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] focus:outline-none transition-all text-black font-medium" />
                                    </div>
                                    <div class="form-control">
                                        <label class="label"><span class="label-text text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ __('Código Postal') }}</span></label>
                                        <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="input input-bordered bg-gray-50 border-gray-200 focus:bg-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] focus:outline-none transition-all text-black font-medium" />
                                    </div>
                                    <div class="form-control">
                                        <label class="label"><span class="label-text text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ __('País') }}</span></label>
                                        <input type="text" name="country" value="{{ old('country', $user->country) }}" class="input input-bordered bg-gray-50 border-gray-200 focus:bg-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] focus:outline-none transition-all text-black font-medium" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex justify-end">
                        <button type="submit" class="btn bg-black text-[#D4AF37] hover:bg-gray-800 border border-[#D4AF37]/30 hover:border-[#D4AF37] px-10 h-14 normal-case text-lg font-black shadow-xl transition-all hover:scale-105">
                            {{ __('Guardar cambios') }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdown-menu');
            menu.classList.toggle('hidden');
            if (!menu.classList.contains('hidden')) {
                document.getElementById('lang-search').focus();
            }
        }

        function filterLanguages() {
            const input = document.getElementById('lang-search');
            const filter = input.value.toLowerCase();
            const ul = document.getElementById('lang-list');
            const li = ul.getElementsByTagName('li');

            for (let i = 0; i < li.length; i++) {
                const a = li[i].getElementsByTagName("a")[0];
                // Buscamos solo en el texto del nombre del idioma, ignorando el SVG
                const nameSpan = a.querySelector('.lang-name');
                const txtValue = nameSpan ? nameSpan.textContent || nameSpan.innerText : '';

                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }

        // Cerrar si clic fuera
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('language-dropdown');
            const menu = document.getElementById('dropdown-menu');
            if (dropdown && menu && !dropdown.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</x-layouts.layout>
