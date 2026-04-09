<x-layouts.layout title='{{__("Crear Cuenta")}} - Lectio'>

    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center bg-brand-bg px-4 py-12">
        <div class="card bg-white shadow-xl w-full max-w-md border border-gray-100">
            <div class="card-body p-8 sm:p-10">

                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-block mb-4">
                        <img src="{{ asset('img/logo.webp') }}" alt="Lectio" class="h-12 w-auto mx-auto">
                    </a>
                    <h1 class="text-3xl font-serif font-bold text-gray-900">{{__("Crear cuenta")}}</h1>
                    <p class="text-gray-500 text-sm mt-2">{{__("Únete a nuestra comunidad de lectores")}}</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700">{{__("Nombre completo")}}</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="input input-bordered w-full focus:border-brand-red focus:outline-none bg-gray-50"
                               placeholder="Tu nombre" />
                        @error('name')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700">{{__("Correo electrónico")}}</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               class="input input-bordered w-full focus:border-brand-red focus:outline-none bg-gray-50"
                               placeholder="ejemplo@correo.com" />
                        @error('email')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700">{{__("Contraseña")}}</span>
                        </label>
                        <input type="password" name="password" required autocomplete="new-password"
                               class="input input-bordered w-full focus:border-brand-red focus:outline-none bg-gray-50"
                               placeholder="Mínimo 8 caracteres" />
                        @error('password')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700">{{__("Confirmar contraseña")}}</span>
                        </label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                               class="input input-bordered w-full focus:border-brand-red focus:outline-none bg-gray-50"
                               placeholder="Repite tu contraseña" />
                        @error('password_confirmation')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit" class="btn bg-gray-900 text-white hover:bg-gray-700 border-none w-full text-lg normal-case font-medium">
                            {{__("Registrarse")}}
                        </button>
                    </div>
                </form>

                {{-- --- SECCIÓN AÑADIDA: REGISTRO CON GOOGLE --- --}}
                <div class="mt-6">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-white text-gray-500">{{__("O regístrate con")}}</span>
                        </div>
                    </div>

                    <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="h-5 w-5" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        {{ __('Google') }}
                    </a>
                </div>
                {{-- --- FIN DE SECCIÓN GOOGLE --- --}}

                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        {{__("¿Ya tienes una cuenta?")}}
                        <a href="{{ route('login') }}" class="link link-hover text-brand-red font-bold">
                            {{__("Inicia sesión")}}
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>

</x-layouts.layout>
