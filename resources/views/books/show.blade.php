<x-layouts.layout title="{{ $book->title }} - Lectio">

    <div class="container mx-auto px-6 py-12">

        <div class="grid grid-cols-1 lg:grid-cols-[35%_auto] gap-12 items-start">

            <div class="flex flex-col gap-6 lg:sticky lg:top-24 lg:self-start">
                <a href="{{ route('catalogo') }}" class="inline-flex items-center text-gray-500 hover:text-[#D4AF37] transition font-medium w-fit group text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{__("Volver al catálogo")}}
                </a>

                <div class="w-full bg-gray-50 p-2 rounded-lg border border-[#D4AF37]/20 flex justify-center items-center shadow-sm">
                    <img src="{{ asset($book->image_url) }}"
                         alt="{{ $book->title }}"
                         class="w-[75%] h-auto object-contain rounded-sm transform hover:scale-[1.02] transition duration-500">
                </div>
            </div>

            <div>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-black mb-2 leading-tight">{{ $book->title }}</h1>
                <p class="text-xl text-gray-500 mb-4">{{__("por")}} <span class="text-black font-bold">{{ $book->author }}</span></p>

                <div class="flex items-center gap-2 mb-8 pb-8 border-b border-gray-100">
                    <div class="rating rating-sm rating-half">
                        @for($i=1; $i<=5; $i++)
                            <input type="radio" class="mask mask-star-2 bg-[#D4AF37]" @if(round($book->rating) == $i) checked @endif disabled />
                        @endfor
                    </div>
                    <span class="text-gray-500 text-sm font-medium">{{ $book->rating }} ({{ $book->reviews_count }} {{__("reseñas")}})</span>
                </div>

                <div class="mb-10">
                    <h3 class="font-bold text-lg mb-3 font-serif text-black">{{__("Sinopsis")}}</h3>
                    <p class="text-gray-600 leading-relaxed text-justify text-lg">
                        {{ $book->synopsis }}
                    </p>
                </div>

                <div class="mb-8">
                    <h3 class="font-bold text-lg mb-4 font-serif text-black border-l-4 border-[#D4AF37] pl-3">{{__("Elige tu formato")}}</h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($book->formats as $index => $format)
                            @php
                                // Asignamos un icono chulo dependiendo del formato
                                $icon = '📕';
                                if(stripos($format->type, 'audio') !== false) $icon = '🎧';
                                if(stripos($format->type, 'e-book') !== false || stripos($format->type, 'digital') !== false) $icon = '📱';

                                // El primer elemento sale seleccionado por defecto
                                $isSelected = $index === 0;
                            @endphp

                            <div onclick="selectFormat({{ $format->id }}, {{ $format->price }}, this)"
                                 class="format-card border-2 rounded-lg p-3 text-center cursor-pointer transition-all duration-300 group
                                        {{ $isSelected ? 'border-[#D4AF37] bg-black' : 'border-gray-200 bg-white hover:border-[#D4AF37]/50' }}">

                                <div class="text-2xl mb-1">{{ $icon }}</div>
                                <div class="type-text font-bold text-sm {{ $isSelected ? 'text-[#D4AF37]' : 'text-gray-900 group-hover:text-[#D4AF37]' }}">
                                    {{ $format->type }}
                                </div>
                                <div class="price-text font-bold text-sm mt-1 {{ $isSelected ? 'text-white' : 'text-gray-500' }}">
                                    {{ number_format($format->price, 2) }}€
                                </div>
                            </div>
                        @empty
                            <p class="text-red-500 text-sm italic">No hay formatos disponibles para este libro.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mb-8 pt-6 border-t border-[#D4AF37]/30">
                    <p class="text-sm text-gray-500 mb-1 uppercase font-black tracking-widest text-[10px]">{{__("Precio (IVA incluido)")}}</p>

                    @php
                        $defaultPrice = $book->formats->first() ? $book->formats->first()->price : 0;
                        $defaultFormatId = $book->formats->first() ? $book->formats->first()->id : '';
                    @endphp

                    <div id="main-price" class="text-4xl font-serif font-bold text-black mb-6 transition-opacity duration-300">
                        {{ number_format($defaultPrice, 2) }}€
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <form action="{{ route('cart.add', $book->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="format_id" id="input-format-add" value="{{ $defaultFormatId }}">
                            <button type="submit" class="btn btn-outline border-2 border-black text-black hover:bg-black hover:text-[#D4AF37] w-full gap-2 normal-case text-lg h-12 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                {{__("Añadir al carrito")}}
                            </button>
                        </form>

                        <form action="{{ route('cart.add', $book->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="action" value="buy_now">
                            <input type="hidden" name="format_id" id="input-format-buy" value="{{ $defaultFormatId }}">
                            <button type="submit" class="btn bg-black text-[#D4AF37] hover:bg-gray-900 border-none w-full normal-case text-lg h-12 shadow-lg transition-all">
                                {{__("Comprar ahora")}}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 mb-10 grid grid-cols-2 gap-y-4 text-sm border border-gray-100">
                    <div><span class="block text-gray-400 uppercase font-bold text-[10px] mb-1">{{__("Editorial")}}</span><span class="font-bold text-black">{{ $book->publisher }}</span></div>
                    <div><span class="block text-gray-400 uppercase font-bold text-[10px] mb-1">{{__("Fecha")}}</span><span class="font-bold text-black">{{ $book->published_date }}</span></div>
                    <div><span class="block text-gray-400 uppercase font-bold text-[10px] mb-1">{{__("Idioma")}}</span><span class="font-bold text-black">{{ $book->language }}</span></div>
                    <div><span class="block text-gray-400 uppercase font-bold text-[10px] mb-1">{{__("Páginas")}}</span><span class="font-bold text-black">{{ $book->pages }}</span></div>
                </div>

                <div>
                    <h3 class="font-bold text-2xl font-serif mb-6 text-black border-l-4 border-[#D4AF37] pl-3">{{__("Reseñas de lectores")}}</h3>
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="border-b border-gray-100 pb-6 last:border-0">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-black">{{ $review['user'] }}</span>
                                    <span class="text-xs font-bold text-[#D4AF37]">{{ $review['date'] }}</span>
                                </div>
                                <div class="rating rating-xs mb-2">
                                    @for($i=1; $i<=5; $i++)
                                        <input type="radio" class="mask mask-star-2 bg-[#D4AF37]" @if($review['rating'] >= $i) checked @endif disabled />
                                    @endfor
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ $review['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function selectFormat(formatId, price, element) {
            // 1. Animamos y cambiamos el precio principal
            const priceDisplay = document.getElementById('main-price');
            priceDisplay.style.opacity = '0'; // Desvanece

            setTimeout(() => {
                priceDisplay.innerText = parseFloat(price).toFixed(2) + '€';
                priceDisplay.style.opacity = '1'; // Aparece con el nuevo precio
            }, 150);

            // 2. Reseteamos TODAS las tarjetas al estado "inactivo" (blanco y gris)
            document.querySelectorAll('.format-card').forEach(card => {
                card.className = "format-card border-2 border-gray-200 bg-white hover:border-[#D4AF37]/50 rounded-lg p-3 text-center cursor-pointer transition-all duration-300 group";
                card.querySelector('.type-text').className = "type-text font-bold text-sm text-gray-900 group-hover:text-[#D4AF37]";
                card.querySelector('.price-text').className = "price-text font-bold text-sm mt-1 text-gray-500";
            });

            // 3. Iluminamos la tarjeta seleccionada con el estilo Black & Gold
            element.className = "format-card border-2 border-[#D4AF37] bg-black rounded-lg p-3 text-center cursor-pointer transition-all duration-300 group";
            element.querySelector('.type-text').className = "type-text font-bold text-sm text-[#D4AF37]";
            element.querySelector('.price-text').className = "price-text font-bold text-sm mt-1 text-white";

            // 4. Actualizamos los formularios ocultos para enviar el ID correcto al carrito
            document.getElementById('input-format-add').value = formatId;
            document.getElementById('input-format-buy').value = formatId;
        }
    </script>
</x-layouts.layout>
