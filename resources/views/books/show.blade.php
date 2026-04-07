<x-layouts.layout title="{{ $book->title }} - Lectio">

    <div class="container mx-auto px-6 py-12">

        <div class="grid grid-cols-1 lg:grid-cols-[35%_auto] gap-12 items-start">

            <div class="flex flex-col gap-6 lg:sticky lg:top-24 lg:self-start">
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-500 hover:text-[#D4AF37] transition font-medium w-fit group text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{__("Volver")}}
                </a>

                <div class="w-full bg-gray-50 p-2 rounded-lg border border-[#D4AF37]/20 flex justify-center items-center shadow-sm relative">

                    {{-- NUEVO: BOTÓN WISHLIST SOBRE LA IMAGEN --}}
                    @auth
                        <form action="{{ route('wishlist.toggle', $book->id) }}" method="POST" class="absolute top-4 right-4 z-10">
                            @csrf
                            <button type="submit" class="bg-white/80 backdrop-blur p-2 rounded-full shadow hover:scale-110 transition">
                                <svg class="w-6 h-6 {{ isset($inWishlist) && $inWishlist ? 'text-red-500 fill-current' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </form>
                    @endauth

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
                            <input type="radio" class="mask mask-star-2 bg-[#D4AF37]" @if(round($book->average_rating) == $i) checked @endif disabled />
                        @endfor
                    </div>
                    <span class="text-gray-500 text-sm font-medium">{{ $book->average_rating }} ({{ $book->reviews_count }} {{__("reseñas")}})</span>
                </div>

                <div class="mb-10">
                    <h3 class="font-bold text-lg mb-3 font-serif text-black">{{__("Sinopsis")}}</h3>
                    <p class="text-gray-600 leading-relaxed text-justify text-lg">
                        {{ $book->description ?? $book->synopsis }}
                    </p>
                </div>

                <div class="mb-8">
                    <h3 class="font-bold text-lg mb-4 font-serif text-black border-l-4 border-[#D4AF37] pl-3">{{__("Elige tu formato")}}</h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($book->formats as $index => $format)
                            @php
                                $icon = '📕';
                                if(stripos($format->type, 'audio') !== false) $icon = '🎧';
                                if(stripos($format->type, 'e-book') !== false || stripos($format->type, 'digital') !== false) $icon = '📱';
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

                {{-- NUEVO: RECOMENDACIONES CRUZADAS --}}
                @if(isset($recommended) && count($recommended) > 0)

                    {{-- Obtenemos todos los IDs de la lista de deseos del usuario para pintarlos rápido --}}
                    @php
                        $userWishlistIds = Auth::check() ? \App\Models\Wishlist::where('user_id', Auth::id())->pluck('book_id')->toArray() : [];
                    @endphp

                    <div class="mt-12 pt-8 border-t border-gray-100">
                        <h3 class="font-bold text-xl font-serif text-black border-l-4 border-[#D4AF37] pl-3 mb-6">{{__("Lectores que compraron este libro también se llevaron...")}}</h3>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            @foreach($recommended as $recBook)
                                <div class="group relative flex flex-col h-full bg-white rounded-xl border border-gray-100 p-2 shadow-sm hover:shadow-md transition">

                                    {{-- BOTÓN AJAX DE WISHLIST --}}
                                    @auth
                                        @php $isWished = in_array($recBook->id, $userWishlistIds); @endphp
                                        <form action="{{ route('wishlist.toggle', $recBook->id) }}" method="POST" class="wishlist-form absolute top-4 right-4 z-20">
                                            @csrf
                                            <button type="submit" class="bg-white/90 backdrop-blur p-2 rounded-full shadow-sm hover:scale-110 transition duration-200">
                                                <svg class="w-5 h-5 transition-colors duration-300 {{ $isWished ? 'text-red-500 fill-current' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            </button>
                                        </form>
                                    @endauth

                                    {{-- Contenedor de la imagen --}}
                                    <a href="{{ route('books.show', $recBook->id) }}" class="relative aspect-[2/3] rounded-lg overflow-hidden mb-3 shadow-sm border border-gray-100 bg-gray-50 flex items-center justify-center p-2 z-10">
                                        <img src="{{ asset($recBook->image_url) }}" alt="{{ $recBook->title }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition duration-500">
                                    </a>

                                    {{-- Info del libro --}}
                                    <a href="{{ route('books.show', $recBook->id) }}" class="flex flex-col flex-grow z-10">
                                        <h4 class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-[#D4AF37] transition leading-tight">{{ $recBook->title }}</h4>
                                        <p class="text-[10px] text-gray-500 mt-1 mb-2">{{ $recBook->author }}</p>

                                        {{-- El Precio --}}
                                        <div class="mt-auto pt-2">
                                            @php
                                                $recFormat = $recBook->formats->first();
                                                $recPrice = $recFormat ? $recFormat->price : 0;
                                                $recDiscountPrice = $recFormat->discount_price ?? null;
                                            @endphp

                                            @if($recDiscountPrice && $recDiscountPrice < $recPrice)
                                                <div class="flex items-center gap-2">
                                                    <p class="text-sm font-black text-red-600">{{ number_format($recDiscountPrice, 2) }}€</p>
                                                    <p class="text-[11px] text-gray-400 line-through font-medium">{{ number_format($recPrice, 2) }}€</p>
                                                </div>
                                            @else
                                                <p class="text-sm font-black text-black">{{ number_format($recPrice, 2) }}€</p>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-16 pt-8 border-t border-gray-200">
                    <div class="flex justify-between items-end mb-8">
                        <h3 class="font-bold text-2xl font-serif text-black border-l-4 border-[#D4AF37] pl-3">{{__("Reseñas de lectores")}}</h3>
                    </div>

                    @auth
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 mb-8">
                            <h4 class="font-bold text-black mb-4">{{__("Deja tu opinión sobre esta obra")}}</h4>
                            <form action="{{ route('reviews.store', $book->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{__("Valoración")}}</label>
                                    <select name="rating" class="bg-white border border-gray-200 text-black text-sm rounded-lg focus:ring-[#D4AF37] focus:border-[#D4AF37] block w-full p-2.5" required>
                                        <option value="5">5 Estrellas - ¡Obra maestra!</option>
                                        <option value="4">4 Estrellas - Muy recomendado</option>
                                        <option value="3">3 Estrellas - Bueno</option>
                                        <option value="2">2 Estrellas - Regular</option>
                                        <option value="1">1 Estrella - No me gustó</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{__("Tu comentario")}}</label>
                                    <textarea name="comment" rows="3" class="block p-2.5 w-full text-sm text-black bg-white rounded-lg border border-gray-200 focus:ring-[#D4AF37] focus:border-[#D4AF37]" placeholder="{{__('¿Qué te ha parecido el libro?')}}" required></textarea>
                                </div>
                                <button type="submit" class="bg-black text-[#D4AF37] font-bold text-xs uppercase tracking-widest py-3 px-6 rounded-lg hover:bg-gray-900 transition-colors">
                                    {{__("Publicar Reseña")}}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mb-8 text-center">
                            <p class="text-gray-500 text-sm"><a href="{{ route('login') }}" class="text-[#D4AF37] font-bold hover:underline">{{__("Inicia sesión")}}</a> {{__("para dejar una reseña.")}}</p>
                        </div>
                    @endauth

                    <div class="space-y-6">
                        @forelse($book->reviews as $review)
                            <div class="border-b border-gray-100 pb-6 last:border-0">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-black">{{ $review->user->name }}</span>
                                    <span class="text-xs font-bold text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="rating rating-xs mb-2">
                                    @for($i=1; $i<=5; $i++)
                                        <input type="radio" class="mask mask-star-2 bg-[#D4AF37]" @if($review->rating >= $i) checked @endif disabled />
                                    @endfor
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 italic text-sm">{{__("Aún no hay reseñas para este libro. ¡Sé el primero en opinar!")}}</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function selectFormat(formatId, price, element) {
            const priceDisplay = document.getElementById('main-price');
            priceDisplay.style.opacity = '0';

            setTimeout(() => {
                priceDisplay.innerText = parseFloat(price).toFixed(2) + '€';
                priceDisplay.style.opacity = '1';
            }, 150);

            document.querySelectorAll('.format-card').forEach(card => {
                card.className = "format-card border-2 border-gray-200 bg-white hover:border-[#D4AF37]/50 rounded-lg p-3 text-center cursor-pointer transition-all duration-300 group";
                card.querySelector('.type-text').className = "type-text font-bold text-sm text-gray-900 group-hover:text-[#D4AF37]";
                card.querySelector('.price-text').className = "price-text font-bold text-sm mt-1 text-gray-500";
            });

            element.className = "format-card border-2 border-[#D4AF37] bg-black rounded-lg p-3 text-center cursor-pointer transition-all duration-300 group";
            element.querySelector('.type-text').className = "type-text font-bold text-sm text-[#D4AF37]";
            element.querySelector('.price-text').className = "price-text font-bold text-sm mt-1 text-white";

            document.getElementById('input-format-add').value = formatId;
            document.getElementById('input-format-buy').value = formatId;
        }
    </script>

</x-layouts.layout>
