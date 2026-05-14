<x-layouts.layout title="Confirmar Identidad - Lectio">
    <div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-[#D4AF37]"></div>

            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-[#D4AF37]/10 mb-4">
                    <svg class="h-6 w-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <h2 class="mt-2 text-3xl font-serif font-bold text-black uppercase tracking-widest">{{ __('Área Segura') }}</h2>
                <p class="mt-4 text-sm text-gray-500 italic font-medium">
                    {{ __('Estás intentando acceder a un área protegida de Lectio. Por tu seguridad, confirma tu contraseña antes de continuar.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-6">
                @csrf

                <div>
                    <label for="password" class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                        {{ __('Tu Contraseña') }}
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" autofocus
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold text-black focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37] outline-none transition-all"
                           placeholder="••••••••">

                    @error('password')
                    <p class="text-red-500 text-xs font-bold mt-2 uppercase tracking-wide">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-black text-[#D4AF37] font-black py-4 rounded-2xl shadow-lg hover:bg-gray-900 active:scale-95 transition-all uppercase tracking-[0.2em] text-xs border border-[#D4AF37]/20 flex justify-center items-center gap-2">
                        {{ __('Confirmar Identidad') }}
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="javascript:history.back()" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-black transition-colors">
                        {{ __('Cancelar y volver') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.layout>
