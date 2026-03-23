<x-layouts.layout>
    <div class="container mx-auto px-6 py-12">

        <section class="mb-16">
            <h2 class="text-3xl text-brand-red font-serif font-bold mb-8 flex items-center gap-2">
                <span>🔥</span>{{__("Ofertas del Mes")}}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                @foreach($descuentos as $libro)
                    <div class="card bg-white shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 h-full group flex flex-col relative">

                        @if($libro->discount_percent)
                            <div class="absolute top-2 left-2 z-20 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-md animate-pulse">
                                {{ $libro->discount_percent }}
                            </div>
                        @endif

                        <figure class="px-4 pt-4 h-72 bg-gray-50">
                            <a href="{{ route('books.show', $libro->id) }}" class="w-full h-full flex justify-center items-center">
                                <img src="{{ asset($libro->image_url) }}" alt="{{ $libro->title }}" class="h-full object-contain drop-shadow-lg transform group-hover:scale-105 transition duration-300" />
                            </a>
                        </figure>

                        <div class="card-body p-5 items-center text-center flex-grow">
                            <a href="{{ route('books.show', $libro->id) }}" class="hover:text-brand-red transition w-full">
                                <h2 class="card-title text-lg leading-tight font-bold line-clamp-2 text-gray-900 justify-center">{{ $libro->title }}</h2>
                            </a>
                            <p class="text-gray-500 text-sm mb-2">{{ $libro->author }}</p>

                            <div class="card-actions justify-center mt-auto pt-2 border-t border-gray-50 w-full mb-3 flex flex-col items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-900 font-bold text-lg">{{ number_format($libro->hardcover_price, 2) }}€</span>
                                    @if($libro->old_price)
                                        <span class="text-gray-400 text-sm line-through">{{ number_format($libro->old_price, 2) }}€</span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 w-full">
                                <form action="{{ route('cart.add', $libro->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline btn-sm w-full border-gray-300 text-gray-600 hover:bg-brand-red hover:text-white hover:border-brand-red normal-case font-medium">
                                        {{__("Añadir")}}
                                    </button>
                                </form>
                                <form action="{{ route('cart.add', $libro->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="buy_now">
                                    <button type="submit" class="btn bg-gray-900 btn-sm w-full text-white hover:bg-gray-700 border-none normal-case font-medium">
                                        {{__("Comprar")}}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="py-12">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl text-black font-serif font-bold mb-8 flex items-center gap-2 border-l-4 border-[#D4AF37] pl-4">
                    <span>📚</span>{{__("Los más comprados")}}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach($populares as $libro)
                        <div class="card bg-white shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 h-full group flex flex-col relative">

                            @if($libro->is_bestseller)
                                <div class="absolute top-3 right-3 z-20 bg-black text-[#D4AF37] text-[10px] uppercase font-black px-2 py-1 rounded shadow-sm border border-[#D4AF37]/30">
                                    {{__("Bestseller")}}
                                </div>
                            @endif

                            <figure class="px-4 pt-4 h-72 bg-gray-50">
                                <a href="{{ route('books.show', $libro->id) }}" class="w-full h-full flex justify-center items-center">
                                    <img src="{{ asset($libro->image_url) }}" alt="{{ $libro->title }}" class="h-full object-contain drop-shadow-lg transform group-hover:scale-105 transition duration-300" />
                                </a>
                            </figure>

                            <div class="card-body p-5 items-center text-center flex-grow">
                                <a href="{{ route('books.show', $libro->id) }}" class="hover:text-[#D4AF37] transition w-full">
                                    <h2 class="card-title text-lg leading-tight font-bold line-clamp-2 text-gray-900 justify-center">{{ $libro->title }}</h2>
                                </a>
                                <p class="text-gray-500 text-sm mb-2">{{ $libro->author }}</p>

                                <div class="card-actions justify-center mt-auto pt-2 border-t border-gray-50 w-full mb-3">
                                    <span class="text-black font-bold text-lg">{{ number_format($libro->hardcover_price, 2) }}€</span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 w-full">
                                    <form action="{{ route('cart.add', $libro->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm w-full border-black text-black hover:bg-black hover:text-[#D4AF37] normal-case font-medium">
                                            {{__("Añadir")}}
                                        </button>
                                    </form>
                                    <form action="{{ route('cart.add', $libro->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="buy_now">
                                        <button type="submit" class="btn bg-gray-900 btn-sm w-full text-white hover:bg-gray-700 border-none normal-case font-medium">
                                            {{__("Comprar")}}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-layouts.layout>
