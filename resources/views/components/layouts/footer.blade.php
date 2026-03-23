<footer class="bg-white border-t border-gray-100 mt-auto">
    @if(request()->routeIs('home'))
        <div class="footer footer-center p-10 bg-white text-base-content">
            <div class="max-w-2xl">
                <h2 class="text-3xl md:text-4xl text-brand-red font-serif font-bold mb-4">{{__("¿Listo para descubrir tu próxima lectura?")}}</h2>
                <a href="{{ route('catalogo') }}" class="btn bg-gray-900 text-white hover:bg-gray-700 border-none px-8 h-12 text-lg">{{__("Explorar catálogo")}}</a>
            </div>
        </div>
    @endif
    <div class="footer footer-center p-6 bg-white text-gray-500 border-t border-gray-100">
        <aside><p>© 2026 Lectio. {{__("Todos los derechos reservados.")}}</p></aside>
    </div>
</footer>
