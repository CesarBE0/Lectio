<x-layouts.layout title='{{__("Iniciar Sesión")}} - Lectio'>

    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center bg-brand-bg px-4 py-12">
        <div class="card bg-white shadow-xl w-full max-w-md border border-gray-100">
            <div class="card-body p-8 sm:p-10">

                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-block mb-4">
                        <img src="{{ asset('img/logo.webp') }}" alt="Lectio" class="h-12 w-auto mx-auto">
                    </a>
                    <h1 class="text-3xl font-serif font-bold text-gray-900">{{__("Bienvenido de nuevo")}}</h1>
                    <p class="text-gray-500 text-sm mt-2">{{__("Ingresa a tu cuenta para continuar")}}</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700">{{__("Correo electrónico")}}</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
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
                        <input type="password" name="password" required autocomplete="current-password"
                               class="input input-bordered w-full focus:border-brand-red focus:outline-none bg-gray-50"
                               placeholder="••••••••" />
                        @error('password')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                        @enderror

                        @if (Route::has('password.request'))
                            <label class="label">
                                <a href="{{ route('password.request') }}" class="label-text-alt link link-hover text-brand-red">
                                    {{__("¿Olvidaste tu contraseña?")}}
                                </a>
                            </label>
                        @endif
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="remember" class="checkbox checkbox-sm checkbox-error checked:bg-brand-red checked:border-brand-red" />
                            <span class="label-text text-gray-600">{{__("Recordar mi sesión")}}</span>
                        </label>
                    </div>

                    <div class="form-control mt-4">
                        <button type="submit" class="btn bg-gray-900 text-white hover:bg-gray-700 border-none w-full text-lg normal-case font-medium">
                            {{__("Iniciar sesión")}}
                        </button>
                    </div>

                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            {{__("¿Aún no tienes cuenta?")}}
                            <a href="{{ route('register') }}" class="link link-hover text-brand-red font-bold">
                                {{__("Regístrate gratis")}}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.layout>
