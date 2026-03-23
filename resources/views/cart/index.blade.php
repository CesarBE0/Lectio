<x-layouts.layout title="Carrito de Compra - Lectio">

    <div class="container mx-auto px-4 md:px-6 py-12 min-h-[60vh]">

        @if(session('cart') && count(session('cart')) > 0)

            <h1 class="text-4xl font-serif font-bold text-black mb-2 border-l-4 border-[#D4AF37] pl-3">{{__("Carrito de Compra")}}</h1>
            <p class="text-gray-500 mb-10 pl-4"><span id="cart-count">{{ count(session('cart')) }}</span> {{__("artículos en tu cesta")}}</p>

            <div class="flex flex-col lg:flex-row gap-12 items-start">

                <div class="w-full lg:w-2/3 space-y-6">

                    @foreach(session('cart') as $cartKey => $details)
                        <div id="row-{{ $cartKey }}" class="flex flex-col sm:flex-row gap-6 bg-white p-4 rounded-xl border border-gray-100 shadow-sm transition hover:shadow-md hover:border-[#D4AF37]/30 group">

                            <div class="w-full sm:w-32 h-40 bg-gray-50 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-100 p-2">
                                <img src="{{ asset($details['image_url']) }}" alt="{{ $details['title'] }}" class="h-full w-auto object-contain group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-serif font-bold text-xl text-black leading-tight">
                                                <a href="{{ route('books.show', $details['book_id'] ?? 1) }}" class="hover:text-[#D4AF37] transition-colors">
                                                    {{ $details['title'] }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ $details['author'] }}</p>
                                            <span class="inline-block mt-2 px-2 py-0.5 bg-black text-[#D4AF37] text-[10px] uppercase font-black tracking-widest rounded">
                                                {{ $details['format'] ?? 'Libro' }}
                                            </span>
                                        </div>

                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $cartKey }}">
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition p-1" title="Eliminar del carrito">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="flex justify-between items-end mt-4">

                                    <div class="flex items-center border border-gray-300 rounded-lg h-8 overflow-hidden bg-white">
                                        <button type="button" onclick="updateCart('{{ $cartKey }}', 'decrease')" class="w-8 h-full flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-red-500 transition-colors font-bold text-lg cursor-pointer">
                                            -
                                        </button>

                                        <input type="text" id="qty-{{ $cartKey }}" value="{{ $details['quantity'] }}" class="w-10 text-center border-x border-y-0 border-gray-300 text-black font-bold focus:ring-0 p-0 text-sm h-full bg-gray-50" readonly>

                                        <button type="button" onclick="updateCart('{{ $cartKey }}', 'increase')" class="w-8 h-full flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-green-600 transition-colors font-bold text-lg cursor-pointer">
                                            +
                                        </button>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-xl font-bold text-black" id="item-total-{{ $cartKey }}">
                                            @php
                                                $price = isset($details['discount_price']) ? $details['discount_price'] : $details['price'];
                                                $totalItem = $price * $details['quantity'];
                                            @endphp
                                            {{ number_format($totalItem, 2) }}€
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="w-full lg:w-1/3 lg:sticky lg:top-24">
                    <div class="bg-black p-6 rounded-xl shadow-xl border-t-4 border-[#D4AF37]">
                        <h2 class="text-xl font-serif font-bold text-white mb-6 uppercase tracking-widest text-sm">{{__("Resumen del pedido")}}</h2>

                        <div class="space-y-3 border-b border-gray-800 pb-6 mb-6">
                            <div class="flex justify-between text-gray-400 text-sm">
                                <span>{{__("Subtotal")}}</span>
                                <span id="summary-subtotal" class="font-medium text-white">{{ number_format($subtotal, 2) }}€</span>
                            </div>

                            <div class="flex justify-between text-gray-400 text-sm">
                                <span>{{__("Envío")}}</span>
                                <div id="summary-shipping">
                                    @if($shippingCost == 0)
                                        <span class="font-black text-[#D4AF37]">{{__("GRATIS")}}</span>
                                    @else
                                        <span class="font-medium text-white">{{ number_format($shippingCost, 2) }}€</span>
                                    @endif
                                </div>
                            </div>

                            <div id="upsell-container" class="text-right mt-1">
                                @if($shippingCost > 0 && $subtotal > 0)
                                    <p class="text-[10px] text-gray-500 italic">
                                        Añade <span class="text-[#D4AF37] font-bold">{{ number_format(50 - $subtotal, 2) }}€</span> más para envío gratis.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-8">
                            <span class="text-sm font-bold text-white uppercase">{{__("Total")}} <span class="text-[10px] text-gray-500 block normal-case">(IVA incl.)</span></span>
                            <span id="summary-total" class="text-4xl font-serif font-bold text-[#D4AF37]">{{ number_format($total, 2) }}€</span>
                        </div>

                        <div class="flex flex-col gap-3">
                            @auth
                                <form action="#" method="POST" class="w-full">
                                    @csrf
                                    <a href="{{ route('checkout.index') }}"
                                       class="btn bg-[#D4AF37] hover:bg-[#b5952f] text-black border-none w-full text-lg normal-case font-black h-12 rounded-lg shadow-md transition-colors">
                                        {{ __('Proceder al Pago') }}
                                    </a>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn bg-white hover:bg-gray-200 text-black border-none w-full text-lg normal-case font-black h-12 rounded-lg shadow-md transition-colors flex items-center justify-center">
                                    {{__("Inicia sesión para comprar")}}
                                </a>
                            @endauth

                            <a href="{{ route('catalogo') }}" class="btn btn-outline border-gray-600 text-gray-300 hover:border-[#D4AF37] hover:text-[#D4AF37] hover:bg-transparent w-full normal-case font-medium h-12 rounded-lg transition-colors">
                                {{__("Seguir comprando")}}
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        @else
            <div class="flex flex-col items-center justify-center py-20 text-center animate-fade-in">
                <div class="mb-6 opacity-80 bg-gray-50 p-6 rounded-full border-2 border-[#D4AF37]/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-[#D4AF37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-4xl font-serif font-bold text-black mb-4">{{__("Tu carrito está vacío")}}</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    {{__("Añade algunas historias a tu carrito para comenzar. El catálogo de Lectio te está esperando.")}}
                </p>
                <a href="{{ route('catalogo') }}" class="btn bg-black hover:bg-gray-800 text-[#D4AF37] border-none px-8 text-lg normal-case font-bold h-12 rounded-lg shadow-lg transition-all hover:scale-105">
                    {{__("Explorar catálogo")}}
                </a>
            </div>
        @endif

    </div>

    <script>
        function updateCart(id, action) {
            fetch('{{ route("cart.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json' // Pedimos al servidor que nos devuelva JSON
                },
                body: JSON.stringify({
                    id: id,
                    action: action
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        // 1. Si el producto se ha quedado a 0, lo borramos de la pantalla
                        if (data.new_quantity === 0) {
                            document.getElementById('row-' + id).remove();
                            // Si era el último producto, recargamos para mostrar el "Carrito Vacío"
                            if (data.is_empty) {
                                window.location.reload();
                                return;
                            }
                        } else {
                            // 2. Si no, actualizamos su cantidad y su precio
                            document.getElementById('qty-' + id).value = data.new_quantity;
                            document.getElementById('item-total-' + id).innerText = data.item_total.toFixed(2) + '€';
                        }

                        // 3. Actualizamos la caja negra de resumen
                        document.getElementById('summary-subtotal').innerText = data.subtotal.toFixed(2) + '€';
                        document.getElementById('summary-total').innerText = data.total.toFixed(2) + '€';

                        // 4. Actualizamos el texto del envío (Gratis o 4.99€)
                        const shippingDiv = document.getElementById('summary-shipping');
                        if (data.shippingCost === 0) {
                            shippingDiv.innerHTML = '<span class="font-black text-[#D4AF37]">GRATIS</span>';
                        } else {
                            shippingDiv.innerHTML = '<span class="font-medium text-white">' + data.shippingCost.toFixed(2) + '€</span>';
                        }

                        // 5. Actualizamos el mensaje de "Te faltan X€ para envío gratis"
                        const upsellDiv = document.getElementById('upsell-container');
                        if (data.shippingCost > 0 && data.subtotal > 0) {
                            const faltan = (50 - data.subtotal).toFixed(2);
                            upsellDiv.innerHTML = `<p class="text-[10px] text-gray-500 italic">Añade <span class="text-[#D4AF37] font-bold">${faltan}€</span> más para envío gratis.</p>`;
                        } else {
                            upsellDiv.innerHTML = '';
                        }
                    }
                })
                .catch(error => console.error('Error al actualizar el carrito:', error));
        }
    </script>

</x-layouts.layout>
