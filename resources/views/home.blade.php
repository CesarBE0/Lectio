<x-layouts.layout>
    <div class="container mx-auto px-6 pt-4 pb-12 space-y-12">

        {{-- BANNER PRINCIPAL (FLEXBOX PARA GARANTIZAR QUE ESTÉN LADO A LADO) --}}
        <section class="flex flex-col md:flex-row gap-6 mb-8">

            <div class="w-full md:w-2/3 bg-black text-white p-8 md:p-12 rounded-3xl flex flex-col justify-between border border-[#D4AF37]/20 relative overflow-hidden shadow-2xl group">
                <div class="absolute inset-0 opacity-10 group-hover:opacity-15 transition-opacity" style="background-image: url('data:image/svg+xml,%3Csvg width=%2252%22 height=%2226%22 viewBox=%220 0 52 26%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath d=%22M10 10c0-2.21-1.79-4-4-4s-4 1.79-4 4 1.79 4 4 4 4-1.79 4-4z%22 fill=%22%23D4AF37%22/%3E%3C/svg%3E');"></div>

                <div class="relative z-10 space-y-4">
                    <span class="text-[11px] uppercase font-black tracking-[0.4em] text-[#D4AF37]">
                        {{__("Colección Exclusiva 2026")}}
                    </span>
                    <h1 class="text-3xl md:text-5xl font-serif font-bold leading-tight max-w-xl">
                        {{__("La literatura que deja huella en tu biblioteca.")}}
                    </h1>
                    <p class="text-gray-300 text-sm md:text-base max-w-lg leading-relaxed font-medium">
                        {{__("Descubre una selección de obras maestras curadas por expertos. Desde clásicos inmortales hasta narrativa contemporánea de élite.")}}
                    </p>
                </div>

                <div class="relative z-10 pt-6 flex flex-col sm:flex-row items-start sm:items-center gap-6 border-t border-gray-800 mt-6">
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase font-bold text-gray-500 tracking-widest">{{__("Libros desde")}}</span>
                        <span class="text-4xl font-black text-[#D4AF37]">9.99€</span>
                    </div>
                    <a href="{{ Route::has('catalogo') ? route('catalogo') : '#' }}"
                       class="bg-[#D4AF37] text-black font-bold px-10 py-4 rounded-xl hover:bg-[#B8962E] transition-all shadow-lg uppercase text-xs tracking-widest active:scale-95">
                        {{__("Ver Catálogo")}}
                    </a>
                </div>
            </div>

            <div class="w-full md:w-1/3 grid grid-cols-2 md:grid-cols-1 gap-4">
                @php
                    $features = [
                        ['icon' => '📖', 'title' => 'Ediciones Únicas', 'subtitle' => 'Calidad Lectio'],
                        ['icon' => '📦', 'title' => 'Envío Premium', 'subtitle' => 'Rápido y Seguro'],
                        ['icon' => '🕒', 'title' => 'Novedades', 'subtitle' => 'Actualizado hoy'],
                        ['icon' => '🛡️', 'title' => 'Compra Segura', 'subtitle' => 'Garantía Total']
                    ];
                @endphp

                @foreach($features as $f)
                    <div class="bg-white p-5 rounded-3xl border border-gray-100 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow group">
                        <div class="bg-gray-100 p-3 rounded-2xl text-xl group-hover:bg-[#D4AF37]/10 transition-colors">
                            {{ $f['icon'] }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-xs">{{__($f['title'])}}</p>
                            <p class="text-[10px] text-gray-500">{{__($f['subtitle'])}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- SECCIÓN OFERTAS DEL MES --}}
        @if(isset($descuentos) && $descuentos->count() > 0)
            <section class="mb-16">
                <h2 class="text-3xl text-red-600 font-serif font-bold mb-8 flex items-center gap-2">
                    <span>🔥</span>{{__("Ofertas del Mes")}}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach($descuentos as $libro)
                        <div class="card bg-white shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 h-full group flex flex-col relative rounded-2xl overflow-hidden">
                            @if($libro->discount_percent)
                                <div class="absolute top-3 left-3 z-20 bg-red-600 text-white text-[10px] font-black px-2 py-1 rounded shadow-md animate-pulse">
                                    {{ $libro->discount_percent }}
                                </div>
                            @endif
                            <figure class="px-4 pt-4 h-72 bg-gray-50/50">
                                <a href="{{ route('books.show', $libro->id) }}" class="w-full h-full flex justify-center items-center">
                                    <img src="{{ asset($libro->image_url) }}" alt="{{ $libro->title }}" class="h-full object-contain drop-shadow-xl transform group-hover:scale-105 transition duration-500" />
                                </a>
                            </figure>
                            <div class="card-body p-6 text-center flex-grow flex flex-col items-center">
                                <a href="{{ route('books.show', $libro->id) }}" class="hover:text-red-600 transition">
                                    <h2 class="text-base font-bold line-clamp-2 text-gray-900 leading-tight mb-1">{{ $libro->title }}</h2>
                                </a>
                                <p class="text-[11px] text-gray-400 uppercase font-bold tracking-tighter mb-4">{{ $libro->author }}</p>
                                <div class="mt-auto w-full">
                                    <div class="flex items-center justify-center gap-3 mb-4">
                                        <span class="text-xl font-black text-black">{{ number_format($libro->hardcover_price ?? 0, 2) }}€</span>
                                        @if($libro->old_price)
                                            <span class="text-xs text-gray-400 line-through font-medium">{{ number_format($libro->old_price, 2) }}€</span>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <form action="{{ route('cart.add', $libro->id) }}" method="POST">@csrf<button type="submit" class="w-full border border-gray-200 text-gray-700 text-[10px] font-black uppercase py-2.5 rounded-lg hover:bg-gray-50 transition-colors">{{__("Añadir")}}</button></form>
                                        <form action="{{ route('cart.add', $libro->id) }}" method="POST">@csrf<input type="hidden" name="action" value="buy_now"><button type="submit" class="w-full bg-black text-[#D4AF37] text-[10px] font-black uppercase py-2.5 rounded-lg hover:bg-gray-900 transition-colors">{{__("Comprar")}}</button></form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- SECCIÓN LOS MÁS COMPRADOS (Solo aparece si hay ventas reales) --}}
        @if($populares->count() > 0)
            <section class="py-12">
                <h2 class="text-3xl text-black font-serif font-bold mb-8 flex items-center gap-2 border-l-4 border-[#D4AF37] pl-4">
                    <span>📚</span>{{__("Los más comprados")}}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach($populares as $libro)
                        <div class="card bg-white shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 h-full group flex flex-col relative rounded-2xl overflow-hidden">

                            {{-- Etiqueta Top Ventas automática --}}
                            <div class="absolute top-3 right-3 z-20 bg-black text-[#D4AF37] text-[9px] uppercase font-black px-2 py-1 rounded border border-[#D4AF37]/30 shadow-sm">
                                {{__("Top Ventas")}}
                            </div>

                            <figure class="px-4 pt-4 h-72 bg-gray-50/50">
                                <a href="{{ route('books.show', $libro->id) }}" class="w-full h-full flex justify-center items-center">
                                    <img src="{{ asset($libro->image_url) }}" alt="{{ $libro->title }}" class="h-full object-contain drop-shadow-xl transform group-hover:scale-105 transition duration-500" />
                                </a>
                            </figure>
                            <div class="card-body p-6 text-center flex-grow flex flex-col items-center">
                                <a href="{{ route('books.show', $libro->id) }}" class="hover:text-[#D4AF37] transition">
                                    <h2 class="text-base font-bold line-clamp-2 text-gray-900 leading-tight mb-1">{{ $libro->title }}</h2>
                                </a>
                                <p class="text-[11px] text-gray-400 uppercase font-bold tracking-tighter mb-4">{{ $libro->author }}</p>
                                <div class="mt-auto w-full">
                                    <div class="mb-4">
                                        <span class="text-xl font-black text-black">{{ number_format($libro->hardcover_price ?? 0, 2) }}€</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <form action="{{ route('cart.add', $libro->id) }}" method="POST">@csrf<button type="submit" class="w-full border border-black text-black text-[10px] font-black uppercase py-2.5 rounded-lg hover:bg-black hover:text-[#D4AF37] transition-all">{{__("Añadir")}}</button></form>
                                        <form action="{{ route('cart.add', $libro->id) }}" method="POST">@csrf<input type="hidden" name="action" value="buy_now"><button type="submit" class="w-full bg-gray-900 text-white text-[10px] font-black uppercase py-2.5 rounded-lg hover:bg-gray-700 transition-colors">{{__("Comprar")}}</button></form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-layouts.layout>
