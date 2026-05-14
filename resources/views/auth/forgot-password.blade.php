<x-layouts.layout title="Recuperar Contraseña - Lectio">
    <div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-[#D4AF37]"></div>

            <div class="text-center">
                <h2 class="mt-2 text-3xl font-serif font-bold text-black uppercase tracking-widest">{{ __('Recuperar Acceso') }}</h2>
                <p class="mt-4 text-sm text-gray-500 italic font-medium">
                    {{ __('¿Has olvidado tu contraseña? No te preocupes. Introduce tu correo electrónico y te enviaremos las instrucciones para devolverte el acceso a la biblioteca de Lectio.') }}
                </p>
            </div>

            @if (session('status'))
                <div class="p-4 bg-[#D4AF37]/10 border-l-4 border-[#D4AF37] text-gray-800 rounded-r shadow-sm">
                    <p class="font-bold text-xs uppercase tracking-widest mb-1">{{ __('Revisa tu bandeja:') }}</p>
                    <p class="text-sm">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-6">
                @csrf

                <div>
                    <label for="email" class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                        {{ __('Correo Electrónico') }}
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold text-black focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37] outline-none transition-all"
                           placeholder="tu@email.com">

                    @error('email')
                    <p class="text-red-500 text-xs font-bold mt-2 uppercase tracking-wide">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full bg-black text-[#D4AF37] font-black py-4 rounded-2xl shadow-lg hover:bg-gray-900 active:scale-95 transition-all uppercase tracking-[0.2em] text-xs border border-[#D4AF37]/20">
                        {{ __('Enviar enlace de recuperación') }}
                    </button>
                </div>

                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        {{ __('Volver al inicio de sesión') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.layout>
