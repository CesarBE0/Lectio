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

                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            {{__("¿Ya tienes una cuenta?")}}
                            <a href="{{ route('login') }}" class="link link-hover text-brand-red font-bold">
                                {{__("Inicia sesión")}}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.layout>
