<x-layouts.layout title='{{__("Catálogo")}} - Lectio'>

    @php
        // Obtenemos los IDs de la lista de deseos del usuario
        $userWishlistIds = Auth::check() ? \App\Models\Wishlist::where('user_id', Auth::id())->pluck('book_id')->toArray() : [];
    @endphp

    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 md:px-6 py-12">

            <div class="text-center mb-10 border-b-2 border-[#D4AF37] pb-8 max-w-2xl mx-auto">
                <h1 class="text-4xl font-serif text-black font-bold mb-2 uppercase tracking-widest">{{__("Nuestro Catálogo")}}</h1>
                <p class="text-[#D4AF37] font-bold text-sm">{{__("Página")}} {{ $books->currentPage() }} {{__("de")}} {{ $books->lastPage() }}</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">

                <aside class="w-full lg:w-1/4 lg:sticky lg:top-24">
                    <div class="bg-black shadow-xl rounded-xl p-6 border-t-4 border-[#D4AF37]">
                        <h3 class="font-bold text-lg mb-6 flex items-center gap-2 text-white uppercase tracking-widest text-sm">
                            <span class="text-[#D4AF37]">🔍</span> {{__("Filtrar búsqueda")}}
                        </h3>

                        <form action="{{ route('catalogo') }}" method="GET" class="space-y-5">

                            <div>
                                <label class="block text-[10px] text-gray-400 uppercase font-black mb-1">{{__("Palabra Clave")}}</label>
                                <input type="text" id="live-search" name="search" value="{{ request('search') }}" placeholder="Título o autor..." class="w-full bg-gray-900 border border-gray-700 text-white rounded p-2 text-sm focus:border-[#D4AF37] focus:outline-none transition-colors" />
                            </div>

                            <div>
                                <label class="block text-[10px] text-gray-400 uppercase font-black mb-1">{{__("Género")}}</label>
                                <select name="category" onchange="this.form.submit()" class="w-full bg-gray-900 border border-gray-700 text-white rounded p-2 text-sm focus:border-[#D4AF37] focus:outline-none transition-colors">
                                    <option value="">{{__("Todos los géneros")}}</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] text-gray-400 uppercase font-black mb-1">{{__("Rango de Precio (€)")}}</label>
                                <div class="flex gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Mín" class="w-1/2 bg-gray-900 border border-gray-700 text-white rounded p-2 text-sm focus:border-[#D4AF37] focus:outline-none" step="0.01">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Máx" class="w-1/2 bg-gray-900 border border-gray-700 text-white rounded p-2 text-sm focus:border-[#D4AF37] focus:outline-none" step="0.01">
                                </div>
                            </div>

                            <div class="pt-4 flex flex-col gap-3">
                                <button type="submit" class="w-full bg-[#D4AF37] text-black font-black uppercase text-xs py-3 rounded hover:bg-[#b5952f] transition-colors">
                                    {{__("Aplicar filtros")}}
                                </button>
                                <a href="{{ route('catalogo') }}" class="w-full text-center border border-gray-600 text-gray-400 font-bold text-xs py-2 rounded hover:text-white hover:border-gray-400 transition-colors">
                                    {{__("Limpiar Todo")}}
                                </a>
                            </div>
                        </form>
                    </div>
                </aside>

                <main class="w-full lg:w-3/4">

                    @if($books->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="catalog-grid">
                            @foreach($books as $book)
                                @php
                                    $minPrice = $book->formats->min('price') ?? 0;
                                    $defaultFormatId = $book->formats->first() ? $book->formats->first()->id : '';
                                @endphp

                                <div class="book-card card bg-white shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 h-full group flex flex-col relative">

                                    {{-- Etiqueta de Categoría (Izquierda) --}}
                                    <div class="absolute top-3 left-3 z-20 bg-black text-[#D4AF37] text-[10px] uppercase font-black px-2 py-1 rounded shadow-sm border border-[#D4AF37]/30">
                                        {{ $book->category }}
                                    </div>

                                    {{-- BOTÓN AJAX DE WISHLIST (Derecha) --}}
                                    @auth
                                        @php $isWished = in_array($book->id, $userWishlistIds); @endphp
                                        <form action="{{ route('wishlist.toggle', $book->id) }}" method="POST" class="wishlist-form absolute top-3 right-3 z-20">
                                            @csrf
                                            <button type="submit" class="bg-white/90 backdrop-blur p-2 rounded-full shadow-sm hover:scale-110 transition duration-200">
                                                <svg class="w-5 h-5 transition-colors duration-300 {{ $isWished ? 'text-red-500 fill-current' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            </button>
                                        </form>
                                    @endauth

                                    <figure class="px-4 pt-4 h-72 bg-gray-50">
                                        <a href="{{ route('books.show', $book->id) }}" class="w-full h-full flex justify-center items-center">
                                            <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" class="h-full object-contain drop-shadow-lg transform group-hover:scale-105 transition duration-300" />
                                        </a>
                                    </figure>

                                    <div class="card-body p-5 items-center text-center flex-grow">
                                        <a href="{{ route('books.show', $book->id) }}" class="hover:text-[#D4AF37] transition w-full">
                                            <h2 class="card-title text-lg leading-tight font-bold line-clamp-2 text-gray-900 justify-center">{{ $book->title }}</h2>
                                        </a>
                                        <p class="book-author text-gray-500 text-sm mb-2">{{ $book->author }}</p>

                                        <div class="card-actions justify-center mt-auto pt-2 border-t border-gray-50 w-full mb-3 flex items-center gap-1">
                                            <span class="text-gray-400 text-xs">{{__("Desde")}}</span>
                                            <span class="text-black font-bold text-lg">{{ number_format($minPrice, 2) }}€</span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 w-full">
                                            <form action="{{ route('cart.add', $book->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="format_id" value="{{ $defaultFormatId }}">
                                                <button type="submit" class="btn btn-outline btn-sm w-full border-black text-black hover:bg-black hover:text-[#D4AF37] normal-case font-medium">
                                                    {{__("Añadir")}}
                                                </button>
                                            </form>

                                            <form action="{{ route('cart.add', $book->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="buy_now">
                                                <input type="hidden" name="format_id" value="{{ $defaultFormatId }}">
                                                <button type="submit" class="btn bg-gray-900 btn-sm w-full text-white hover:bg-gray-700 border-none normal-case font-medium">
                                                    {{__("Comprar")}}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-12 flex justify-center">
                            {{ $books->links() }}
                        </div>

                    @else
                        <div class="flex flex-col items-center justify-center py-24 text-center bg-white rounded-xl border border-gray-100 shadow-sm">
                            <div class="text-6xl mb-6 opacity-50 grayscale">📚</div>
                            <h3 class="text-2xl font-serif font-bold text-black">{{__("No encontramos libros")}}</h3>
                            <p class="text-gray-500 mt-2 max-w-md">{{__("No hay coincidencias con esos filtros. Intenta ampliar tu búsqueda o limpiar los campos.")}}</p>
                            <a href="{{ route('catalogo') }}" class="mt-6 bg-black text-[#D4AF37] px-6 py-2 rounded font-bold uppercase text-xs hover:bg-gray-800 transition-colors">{{__("Restablecer filtros")}}</a>
                        </div>
                    @endif

                </main>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('live-search');
            const bookCards = document.querySelectorAll('.book-card');

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    // Convertimos lo que el usuario escribe a minúsculas
                    const searchTerm = e.target.value.toLowerCase().trim();

                    bookCards.forEach(card => {
                        // Cogemos el texto del título y del autor de cada tarjeta
                        const title = card.querySelector('.card-title').textContent.toLowerCase();
                        const author = card.querySelector('.book-author').textContent.toLowerCase();

                        // Si el título o el autor contienen lo que escribimos, mostramos la tarjeta
                        if (title.includes(searchTerm) || author.includes(searchTerm)) {
                            card.style.display = ''; // Vuelve a su estado natural (flex)
                        } else {
                            card.style.display = 'none'; // La ocultamos
                        }
                    });
                });
            }
        });
    </script>
</x-layouts.layout>
