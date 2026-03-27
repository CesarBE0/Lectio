<x-layouts.layout title="{{ __('Finalizar Compra') }} - Lectio">
    <div class="bg-gray-50 min-h-screen py-16 text-black font-sans">
        <div class="container mx-auto px-4 md:px-6 max-w-6xl">

            <div class="text-center mb-12 border-b border-gray-100 pb-8">
                <h1 class="text-4xl font-serif text-black uppercase tracking-[0.2em] mb-2">{{ __('Finalizar Pedido') }}</h1>
                <p class="text-gray-500 text-sm uppercase tracking-widest">{{ __('Estás a un paso de ampliar tu colección personal') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                <div class="lg:col-span-7">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" class="space-y-10">
                        @csrf

                        <div class="relative">
                            <div class="absolute left-4 top-10 bottom-0 w-px bg-gray-100 -z-10"></div>

                            <div class="flex items-center gap-4 mb-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#D4AF37] text-white flex items-center justify-center font-bold font-serif text-lg shadow-sm">
                                    1
                                </div>
                                <h2 class="text-2xl font-serif tracking-wide text-black">{{ __('Datos de Envío') }}</h2>
                            </div>

                            <div class="ml-12 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 md:col-span-2">
                                    <label
                                        class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Dirección Completa') }}</label>
                                    <input type="text" name="address" value="{{ old('address') }}" required
                                           class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all placeholder-gray-300"
                                           placeholder="Ej: Paseo de la Independencia, 15, 4ºA">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Ciudad') }}</label>
                                    <input type="text" name="city" value="{{ old('city') }}" required
                                           class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all placeholder-gray-300"
                                           placeholder="Zaragoza">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Código Postal') }}</label>
                                    <input type="text" name="zip" value="{{ old('zip') }}" required
                                           class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all placeholder-gray-300"
                                           placeholder="50001">
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="flex items-center gap-4 mb-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#D4AF37] text-white flex items-center justify-center font-bold font-serif text-lg shadow-sm">
                                    2
                                </div>
                                <h2 class="text-2xl font-serif tracking-wide text-black">{{ __('Método de Pago') }}</h2>
                            </div>

                            <div class="ml-12">
                                <div class="bg-white border border-gray-100 p-8 rounded-sm shadow-sm">
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4 border-b border-gray-100 pb-6">
                                        <label
                                            class="text-xs uppercase text-gray-400 tracking-widest font-bold m-0">{{ __('Detalles de la Tarjeta') }}</label>
                                        <div class="flex items-center gap-4">
                                            <span class="text-xl">💳</span>
                                        </div>
                                    </div>

                                    <div class="space-y-6">
                                        <div>
                                            <label
                                                class="text-[10px] uppercase text-gray-500 tracking-widest font-bold mb-2 block">{{ __('Número de Tarjeta') }}</label>
                                            <div id="card-number-element"
                                                 class="p-3 border border-gray-200 bg-white rounded-sm min-h-[46px] focus-within:border-[#D4AF37] transition-colors"></div>
                                            <div id="card-number-errors" role="alert"
                                                 class="text-[#ef4444] text-xs mt-1 font-bold tracking-wide"></div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-6">
                                            <div>
                                                <label
                                                    class="text-[10px] uppercase text-gray-500 tracking-widest font-bold mb-2 block">{{ __('Caducidad') }}</label>
                                                <div id="card-expiry-element"
                                                     class="p-3 border border-gray-200 bg-white rounded-sm min-h-[46px] focus-within:border-[#D4AF37] transition-colors"></div>
                                                <div id="card-expiry-errors" role="alert"
                                                     class="text-[#ef4444] text-xs mt-1 font-bold tracking-wide"></div>
                                            </div>
                                            <div>
                                                <label
                                                    class="text-[10px] uppercase text-gray-500 tracking-widest font-bold mb-2 block">{{ __('CVC') }}</label>
                                                <div id="card-cvc-element"
                                                     class="p-3 border border-gray-200 bg-white rounded-sm min-h-[46px] focus-within:border-[#D4AF37] transition-colors"></div>
                                                <div id="card-cvc-errors" role="alert"
                                                     class="text-[#ef4444] text-xs mt-1 font-bold tracking-wide"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="card-errors" role="alert"
                                         class="text-[#ef4444] text-xs mt-3 font-bold tracking-wide"></div>
                                </div>
                                <div
                                    class="flex items-center gap-2 mt-4 text-gray-500 text-[10px] uppercase tracking-widest">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#D4AF37]" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Pago 100% seguro y encriptado mediante Stripe</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-white border border-gray-100 p-8 sticky top-8 rounded-sm shadow-sm">
                        <h3 class="text-xl font-serif text-black mb-6 border-b border-gray-100 pb-4">{{ __('Tu Selección') }}
                            <span class="text-[#D4AF37]">({{ count($cartItems) }})</span>
                        </h3>

                        <div class="space-y-5 mb-10 border-b border-gray-100 pb-8 max-h-[400px] overflow-y-auto pr-2">
                            @forelse($cartItems as $item)
                                <div class="flex justify-between items-center group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-14 bg-gray-50 rounded-sm flex-shrink-0 border border-gray-200 flex items-center justify-center overflow-hidden">
                                            @if(isset($item['image_url']))
                                                <img src="{{ asset($item['image_url']) }}"
                                                     alt="{{ $item['title'] ?? 'Libro' }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xs">📚</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-black group-hover:text-[#D4AF37] transition-colors line-clamp-1">{{ $item['title'] ?? 'Título desconocido' }}</p>
                                            <p class="text-[10px] text-gray-500 uppercase tracking-widest">{{ $item['format'] ?? 'Tapa Dura' }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold">{{ isset($item['price']) ? number_format($item['price'], 2, ',', '.') : '0,00' }}€</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center italic py-4">{{ __('Tu carrito está vacío.') }}</p>
                            @endforelse
                        </div>

                        <div class="space-y-4 pt-6 mb-10">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span>{{ number_format($subtotal, 2, ',', '.') }}€</span>
                            </div>

                            <div id="coupon-success-container"
                                 class="{{ session()->has('coupon') ? 'flex' : 'hidden' }} justify-between items-center text-sm pt-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-[#D4AF37] font-bold" id="applied-coupon-text">Cupón ({{ session('coupon')['code'] ?? '' }})</span>
                                    <button type="button" onclick="removeCouponJS()"
                                            class="text-xs text-red-500 hover:text-red-700 uppercase tracking-widest">
                                        [Quitar]
                                    </button>
                                </div>
                                <span class="text-[#D4AF37] font-bold" id="discount-amount-text">-{{ number_format($discountAmount ?? 0, 2, ',', '.') }}€</span>
                            </div>

                            <div id="coupon-form-container"
                                 class="{{ session()->has('coupon') ? 'hidden' : 'flex' }} gap-2 pt-2">
                                <input type="text" id="coupon-code-input" placeholder="¿Tienes un código?"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-sm px-3 py-2 text-xs uppercase tracking-widest text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all">
                                <button type="button" onclick="applyCouponJS()"
                                        class="bg-black text-[#D4AF37] px-4 py-2 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors rounded-sm">
                                    Aplicar
                                </button>
                            </div>

                            <div
                                class="flex justify-between text-sm text-gray-600 items-center pt-2 border-t border-gray-50">
                                <span>Envío Premium</span>
                                <div id="shipping-amount-text">
                                    @if($shipping == 0)
                                        <span
                                            class="text-[#D4AF37] text-xs border border-[#D4AF37] px-2 py-0.5 rounded-sm uppercase tracking-widest font-bold">Gratis</span>
                                    @else
                                        <span
                                            class="text-black font-bold">{{ number_format($shipping, 2, ',', '.') }}€</span>
                                    @endif
                                </div>
                            </div>

                            <div
                                class="flex justify-between text-3xl font-serif text-black pt-5 border-t border-gray-100 mt-5">
                                <span class="font-normal">{{ __('Total') }}</span>
                                <span class="text-black font-serif" id="final-total-text">{{ number_format($total, 2, ',', '.') }}€</span>
                            </div>
                        </div>

                        <button type="submit" form="checkout-form" id="submit-button"
                                class="w-full py-4 bg-[#D4AF37] text-white font-black uppercase tracking-[0.2em] text-sm hover:bg-gray-900 transition-all duration-300 shadow-yellow-900/10 shadow-lg">
                            {{ __('Finalizar y Pagar') }} <span id="button-total-text">{{ number_format($total, 2, ',', '.') }}€</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // --- 1. LÓGICA AJAX DEL CUPÓN ---
        // Definimos la variable global para usarla en Stripe, la vinculamos al <span> del botón
        let currentTotal = '{{ number_format($total, 2, ",", ".") }}€';

        function formatMoney(amount) {
            return new Intl.NumberFormat('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(amount);
        }

        function applyCouponJS() {
            const code = document.getElementById('coupon-code-input').value;
            if (!code) return;

            fetch('{{ route("coupon.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({code: code})
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('coupon-form-container').classList.replace('flex', 'hidden');
                        document.getElementById('coupon-success-container').classList.replace('hidden', 'flex');

                        document.getElementById('applied-coupon-text').innerText = `Cupón (${data.code})`;
                        document.getElementById('discount-amount-text').innerText = `-${formatMoney(data.discountAmount)}€`;

                        updateTotalsUI(data.shipping, data.total);
                        Toast.fire({icon: 'success', title: data.message});
                    } else {
                        Toast.fire({icon: 'error', title: data.message});
                    }
                });
        }

        function removeCouponJS() {
            fetch('{{ route("coupon.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('coupon-form-container').classList.replace('hidden', 'flex');
                        document.getElementById('coupon-success-container').classList.replace('flex', 'hidden');
                        document.getElementById('coupon-code-input').value = '';

                        updateTotalsUI(data.shipping, data.total);
                        Toast.fire({icon: 'success', title: data.message});
                    }
                });
        }

        function updateTotalsUI(shipping, total) {
            const shippingEl = document.getElementById('shipping-amount-text');
            if (shipping === 0) {
                shippingEl.innerHTML = `<span class="text-[#D4AF37] text-xs border border-[#D4AF37] px-2 py-0.5 rounded-sm uppercase tracking-widest font-bold">Gratis</span>`;
            } else {
                shippingEl.innerHTML = `<span class="text-black font-bold">${formatMoney(shipping)}€</span>`;
            }

            const formattedTotal = `${formatMoney(total)}€`;
            document.getElementById('final-total-text').innerText = formattedTotal;
            document.getElementById('button-total-text').innerText = formattedTotal;
            currentTotal = formattedTotal;
        }

        // --- 2. LÓGICA DE STRIPE ---
        const stripe = Stripe('{{ env("STRIPE_KEY") }}');
        const elements = stripe.elements();

        const style = {
            base: {
                color: '#000000',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '15px',
                iconColor: '#D4AF37',
                '::placeholder': {color: '#aab7c4'}
            },
            invalid: {color: '#ef4444', iconColor: '#ef4444'}
        };

        const cardNumberElement = elements.create('cardNumber', {style: style, showIcon: true});
        const cardExpiryElement = elements.create('cardExpiry', {style: style});
        const cardCvcElement = elements.create('cardCvc', {style: style});

        cardNumberElement.mount('#card-number-element');
        cardExpiryElement.mount('#card-expiry-element');
        cardCvcElement.mount('#card-cvc-element');

        function handleStripeError(event, errorElementId) {
            const displayError = document.getElementById(errorElementId);
            if (event.error) {
                displayError.textContent = event.error.message;
                document.getElementById(errorElementId.replace('-errors', '-element')).classList.add('border-[#ef4444]');
            } else {
                displayError.textContent = '';
                document.getElementById(errorElementId.replace('-errors', '-element')).classList.remove('border-[#ef4444]');
            }
        }

        cardNumberElement.on('change', (event) => handleStripeError(event, 'card-number-errors'));
        cardExpiryElement.on('change', (event) => handleStripeError(event, 'card-expiry-errors'));
        cardCvcElement.on('change', (event) => handleStripeError(event, 'card-cvc-errors'));

        const form = document.getElementById('checkout-form');
        const submitButton = document.getElementById('submit-button');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            submitButton.disabled = true;
            submitButton.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> AUTORIZANDO PAGO...`;
            submitButton.classList.add('opacity-70', 'cursor-not-allowed');

            const {token, error} = await stripe.createToken(cardNumberElement);

            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                document.getElementById('card-number-element').classList.add('border-[#ef4444]');

                submitButton.disabled = false;
                // Usamos la variable currentTotal que se actualiza con el JS del cupón
                submitButton.innerHTML = `FINALIZAR Y PAGAR <span id="button-total-text">${currentTotal}</span>`;
                submitButton.classList.remove('opacity-70', 'cursor-not-allowed');
            } else {
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                form.submit();
            }
        });
    </script>

</x-layouts.layout>
