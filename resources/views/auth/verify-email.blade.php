<x-layouts.layout title="Verificar Correo - Lectio">
    <div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-[#D4AF37]"></div>

            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-[#D4AF37]/10 mb-4">
                    <svg class="h-6 w-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <h2 class="mt-2 text-3xl font-serif font-bold text-black uppercase tracking-widest">{{ __('Verificar Correo') }}</h2>
                <p class="mt-4 text-sm text-gray-500 italic font-medium">
                    {{ __('¡Gracias por unirte a Lectio! Antes de empezar a explorar, por favor verifica tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar. Si no lo has recibido, estaremos encantados de enviarte otro.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm">
                    <p class="font-bold text-xs uppercase tracking-widest mb-1">{{ __('Enlace reenviado:') }}</p>
                    <p class="text-sm">{{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo que proporcionaste durante el registro.') }}</p>
                </div>
            @endif

            <div class="mt-8 space-y-5">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full bg-black text-[#D4AF37] font-black py-4 rounded-2xl shadow-lg hover:bg-gray-900 active:scale-95 transition-all uppercase tracking-[0.2em] text-xs border border-[#D4AF37]/20 flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        {{ __('Reenviar correo') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-black transition-colors">
                        {{ __('Cerrar Sesión') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.layout>
