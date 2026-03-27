<x-layouts.layout title="{{ __('Mi Cuenta') }} - Lectio">
    <div class="bg-gray-50 min-h-screen py-16 text-black font-sans">
        <div class="container mx-auto px-4 md:px-6 max-w-4xl">

            <div class="text-center mb-12 border-b border-gray-100 pb-8">
                <h1 class="text-4xl font-serif text-black uppercase tracking-[0.2em] mb-2">{{ __('Mi Cuenta') }}</h1>
                <p class="text-gray-500 text-sm uppercase tracking-widest">{{ __('Gestiona tus datos personales y credenciales') }}</p>
            </div>

            <div class="bg-white border border-gray-100 p-8 md:p-12 rounded-sm shadow-sm">
                @if ($errors->any())
                    <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-10">
                    @csrf

                    <div>
                        <h2 class="text-xl font-serif tracking-wide text-black mb-6 flex items-center gap-3 border-b border-gray-50 pb-4">
                            <span class="text-[#D4AF37]">1.</span> {{ __('Datos Personales') }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 md:col-span-2">
                                <label
                                    class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Nombre Completo') }}</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all">
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label
                                    class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Correo Electrónico') }}</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full bg-gray-50 border border-gray-200 rounded-sm px-4 py-3 text-gray-600 focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all">
                            </div>

                            <div class="space-y-2 md:col-span-2 mt-2">
                                <label
                                    class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Idioma') }}</label>

                                <div class="relative" id="custom-lang-select">
                                    <input type="hidden" name="preferred_language" id="preferred_language"
                                           value="{{ old('preferred_language', $user->preferred_language ?? 'es') }}">

                                    <button type="button" onclick="toggleLangMenu()"
                                            class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all flex items-center justify-between">
                                        <span id="selected-lang-text" class="flex items-center gap-3">
                                            Seleccionando...
                                        </span>
                                        <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                        </svg>
                                    </button>

                                    <div id="lang-menu"
                                         class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-sm shadow-xl max-h-60 overflow-y-auto">
                                        @foreach($languages as $lang)
                                            <div
                                                onclick="selectLang('{{ $lang['code'] }}', '{{ $lang['name'] }}', '{{ $lang['flag_country'] }}')"
                                                class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-colors text-black border-b border-gray-50 last:border-0">
                                                <span
                                                    class="fi fi-{{ $lang['flag_country'] }} w-5 h-4 rounded-sm shadow-sm"></span>
                                                <span class="font-medium">{{ $lang['name'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-serif tracking-wide text-black mb-6 flex items-center gap-3 border-b border-gray-50 pb-4 mt-10">
                            <span class="text-[#D4AF37]">2.</span> {{ __('Dirección de Envío') }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 md:col-span-2">
                                <label
                                    class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Dirección Completa') }}</label>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                       class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all placeholder-gray-300"
                                       placeholder="Ej: Paseo de la Independencia, 15, 4ºA">
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Ciudad') }}</label>
                                <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                       class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all placeholder-gray-300">
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Código Postal') }}</label>
                                <input type="text" name="postal_code"
                                       value="{{ old('postal_code', $user->postal_code) }}"
                                       class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all placeholder-gray-300">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-serif tracking-wide text-black mb-6 flex items-center gap-3 border-b border-gray-50 pb-4 mt-10">
                            <span class="text-[#D4AF37]">3.</span> {{ __('Cambiar Contraseña') }}
                        </h2>
                        <p class="text-xs text-gray-500 mb-6 italic">{{ __('Deja estos campos en blanco si no deseas cambiar tu contraseña actual.') }}</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label
                                    class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Nueva Contraseña') }}</label>
                                <input type="password" name="password"
                                       class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all">
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Confirmar Contraseña') }}</label>
                                <input type="password" name="password_confirmation"
                                       class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="pt-8">
                        <button type="submit"
                                class="w-full md:w-auto px-10 py-4 bg-black text-[#D4AF37] font-black uppercase tracking-[0.2em] text-sm hover:bg-gray-900 transition-all duration-300 shadow-md">
                            {{ __('Guardar Cambios') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        const langData = @json($languages);

        document.addEventListener('DOMContentLoaded', function () {
            const currentCode = document.getElementById('preferred_language').value;
            const currentLang = langData.find(l => l.code === currentCode) || langData[0];
            updateLangButton(currentLang.name, currentLang.flag_country);
        });

        function updateLangButton(name, flagCode) {
            document.getElementById('selected-lang-text').innerHTML = `
                <span class="fi fi-${flagCode} w-5 h-4 rounded-sm shadow-sm"></span>
                <span class="font-medium">${name}</span>
            `;
        }

        function toggleLangMenu() {
            document.getElementById('lang-menu').classList.toggle('hidden');
        }

        function selectLang(code, name, flagCode) {
            document.getElementById('preferred_language').value = code;
            updateLangButton(name, flagCode);
            document.getElementById('lang-menu').classList.add('hidden');
        }

        document.addEventListener('click', function (event) {
            const select = document.getElementById('custom-lang-select');
            if (select && !select.contains(event.target)) {
                document.getElementById('lang-menu').classList.add('hidden');
            }
        });
    </script>
</x-layouts.layout>
