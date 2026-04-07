<x-layouts.layout title="Mi Lista de Deseos - Lectio">

    <div class="container mx-auto px-6 py-12 min-h-[60vh]">
        <div class="mb-8 border-b border-gray-100 pb-6">
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-black mb-2">{{__("Mi Lista de Deseos")}}</h1>
            <p class="text-gray-500">{{__("Los libros que estás deseando leer.")}}</p>
        </div>

        @if($wishlist->isEmpty())
            {{-- ESTADO VACÍO --}}
            <div class="bg-gray-50 rounded-2xl p-12 text-center border border-gray-100">
                <div class="w-20 h-20 mx-auto bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">{{__("Tu lista está vacía")}}</h2>
                <p class="text-gray-500 mb-8">{{__("Aún no has guardado ningún libro para más tarde. Navega por nuestro catálogo y añade los que más te gusten.")}}</p>
                <a href="{{ route('catalogo') }}" class="inline-block bg-black text-[#D4AF37] font-bold text-xs uppercase tracking-widest py-3 px-8 rounded-lg hover:bg-gray-900 transition-transform active:scale-95 shadow-md">
                    {{__("Explorar Catálogo")}}
                </a>
            </div>
        @else
            {{-- CUADRÍCULA DE LIBROS GUARDADOS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($wishlist as $item)
                    @php
                        $book = $item->book;
                        $firstFormat = $book->formats->first();
                        $price = $firstFormat ? $firstFormat->price : 0;
                        $defaultFormatId = $firstFormat ? $firstFormat->id : '';
                    @endphp

                    <div class="group relative flex flex-col h-full bg-white rounded-xl border border-gray-100 p-3 shadow-sm hover:shadow-md transition">

                        {{-- Botón para quitar de la lista (AJAX) --}}
                        <form action="{{ route('wishlist.toggle', $book->id) }}" method="POST" class="wishlist-form absolute top-5 right-5 z-20">
                            @csrf
                            <button type="submit" class="bg-white/90 backdrop-blur p-2 rounded-full shadow hover:scale-110 transition text-red-500 hover:text-gray-400" title="Quitar de la lista">
                                <svg class="w-5 h-5 fill-current transition-colors duration-300" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </form>

                        {{-- Imagen del libro --}}
                        <a href="{{ route('books.show', $book->id) }}" class="block relative aspect-[2/3] rounded-lg overflow-hidden mb-3 bg-gray-50 flex items-center justify-center p-2 z-10">
                            <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition duration-500">
                        </a>

                        {{-- Info del libro --}}
                        <div class="flex flex-col flex-grow z-10">
                            <a href="{{ route('books.show', $book->id) }}">
                                <h4 class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-[#D4AF37] transition leading-tight">{{ $book->title }}</h4>
                                <p class="text-[10px] text-gray-500 mt-1 mb-3">{{ $book->author }}</p>
                            </a>

                            {{-- Precio, Enlace y Botones de compra --}}
                            <div class="mt-auto pt-3 border-t border-gray-50 flex flex-col gap-3">

                                <div class="flex justify-between items-center w-full">
                                    <span class="font-black text-sm">{{ number_format($price, 2) }}€</span>
                                    <a href="{{ route('books.show', $book->id) }}" class="text-[9px] font-bold text-gray-400 hover:text-[#D4AF37] uppercase tracking-widest transition">{{__("Detalles")}}</a>
                                </div>

                                {{-- Los nuevos botones de Añadir y Comprar --}}
                                <div class="grid grid-cols-2 gap-2 w-full">
                                    <form action="{{ route('cart.add', $book->id) }}" method="POST" class="ajax-cart-form w-full">
                                        @csrf
                                        <input type="hidden" name="format_id" value="{{ $defaultFormatId }}">
                                        <button type="submit" class="w-full border border-black text-black text-[10px] font-black uppercase py-2 rounded-lg hover:bg-black hover:text-[#D4AF37] transition-all">
                                            {{__("Añadir")}}
                                        </button>
                                    </form>

                                    <form action="{{ route('cart.add', $book->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="action" value="buy_now">
                                        <input type="hidden" name="format_id" value="{{ $defaultFormatId }}">
                                        <button type="submit" class="w-full bg-gray-900 text-white text-[10px] font-black uppercase py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                            {{__("Comprar")}}
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        @endif
    </div>

    {{-- SCRIPT PARA QUE EL BOTÓN DE QUITAR (EL CORAZÓN) FUNCIONE SIN RECARGAR --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wishForms = document.querySelectorAll('.wishlist-form');

            wishForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const url = this.action;
                    const token = this.querySelector('input[name="_token"]').value;
                    const card = this.closest('.group');

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({})
                    })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                // Si se elimina de la lista con éxito, difuminamos la tarjeta y la ocultamos suavemente
                                card.style.transition = "all 0.3s ease";
                                card.style.opacity = "0";
                                card.style.transform = "scale(0.95)";

                                setTimeout(() => {
                                    card.remove();

                                    // Si era el último libro, recargamos la página para mostrar el estado "Tu lista está vacía"
                                    if(document.querySelectorAll('.group').length === 0) {
                                        window.location.reload();
                                    }
                                }, 300);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
</x-layouts.layout>
