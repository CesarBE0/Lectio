<x-layouts.layout title="Restablecer Contraseña - Lectio">
    <div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-[#D4AF37]"></div>

            <div class="text-center">
                <h2 class="mt-2 text-3xl font-serif font-bold text-black uppercase tracking-widest">{{ __('Nueva Contraseña') }}</h2>
                <p class="mt-4 text-sm text-gray-500 italic font-medium">
                    {{ __('Estás a un paso de recuperar tu cuenta. Introduce y confirma tu nueva contraseña segura.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                        {{ __('Correo Electrónico') }}
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold text-gray-500 focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37] outline-none transition-all cursor-not-allowed" readonly>

                    @error('email')
                    <p class="text-red-500 text-xs font-bold mt-2 uppercase tracking-wide">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                        {{ __('Nueva Contraseña') }}
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold text-black focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37] outline-none transition-all"
                           placeholder="••••••••">

                    @error('password')
                    <p class="text-red-500 text-xs font-bold mt-2 uppercase tracking-wide">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">
                        {{ __('Confirmar Contraseña') }}
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold text-black focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37] outline-none transition-all"
                           placeholder="••••••••">

                    @error('password_confirmation')
                    <p class="text-red-500 text-xs font-bold mt-2 uppercase tracking-wide">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full bg-black text-[#D4AF37] font-black py-4 rounded-2xl shadow-lg hover:bg-gray-900 active:scale-95 transition-all uppercase tracking-[0.2em] text-xs border border-[#D4AF37]/20">
                        {{ __('Actualizar y Entrar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.layout>
