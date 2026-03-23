<x-layouts.layout title="{{ __('Finalizar Compra') }} - Lectio">
    <div class="bg-gray-50 min-h-screen py-16 text-black font-sans">
        <div class="container mx-auto px-4 md:px-6 max-w-6xl">

            <div class="text-center mb-12 border-b border-gray-100 pb-8">
                <h1 class="text-4xl font-serif text-black uppercase tracking-[0.2em] mb-2">{{ __('Finalizar Pedido') }}</h1>
                <p class="text-gray-500 text-sm uppercase tracking-widest">{{ __('Estás a un paso de ampliar tu colección personal') }}</p>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form"
                  class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                @csrf

                <div class="lg:col-span-7 space-y-10">

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
                                <input type="text" name="address" required
                                       class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all placeholder-gray-300"
                                       placeholder="Ej: Paseo de la Independencia, 15, 4ºA">
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Ciudad') }}</label>
                                <input type="text" name="city" required
                                       class="w-full bg-white border border-gray-200 rounded-sm px-4 py-3 text-black focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all placeholder-gray-300"
                                       placeholder="Zaragoza">
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-xs uppercase text-gray-400 tracking-widest font-bold">{{ __('Código Postal') }}</label>
                                <input type="text" name="zip" required
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
                                        <div class="flex items-center justify-center h-6 w-10">
                                            <svg viewBox="0 0 38 12" xmlns="http://www.w3.org/2000/svg"
                                                 class="w-full h-full fill-[#1434CB]">
                                                <path
                                                    d="M14.93 0l-2.45 11.56h3.96L18.89 0h-3.96zm10.74 3.32c-.75-.36-1.92-.76-3.32-.76-3.88 0-6.61 2.06-6.63 5.02-.03 2.18 1.95 3.39 3.44 4.13 1.52.75 2.04 1.24 2.04 1.91-.01 1.03-1.25 1.5-2.41 1.5-1.6 0-2.47-.23-3.79-.81l-.53-.25-.56 3.49c.94.43 2.68.81 4.5.83 4.11 0 6.8-2.03 6.83-5.17.02-1.72-1.01-3.04-3.3-4.14-1.37-.69-2.2-1.14-2.2-1.84 0-.66.74-1.35 2.3-1.35 1.25-.03 2.17.26 2.89.58l.35.16.65-3.29zM28.87 0l-3.39 11.56h4.15l.66-3.32h4.88l.45 3.32h3.65L35.25 0h-6.38zm1.88 3.12l1.17 5.56h-2.82l1.65-5.56zM11.69 0L8.24 7.9 7.84 5.9C7.45 4.3 5.67 2.45 3.52 1.5L4.54 11.56h4.14l6.19-11.56h-3.18z"/>
                                                <path d="M5.44 0H.05L0 .24c2.81.71 5.98 2.43 6.96 4.47l-1.03-4.7z"
                                                      class="fill-[#F5A623]"/>
                                            </svg>
                                        </div>

                                        <div class="flex items-center justify-center h-6 w-9">
                                            <svg viewBox="0 0 24 15" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                 class="w-full h-full">
                                                <path
                                                    d="M15.245 7.5c0-2.31-1.12-4.36-2.845-5.64a7.485 7.485 0 0 0 0 11.28c1.725-1.28 2.845-3.33 2.845-5.64Z"
                                                    fill="#FF5F00"/>
                                                <path
                                                    d="M9.815 1.86A7.48 7.48 0 0 0 7.5 15a7.48 7.48 0 0 0 4.9-1.86 7.486 7.486 0 0 1 0-11.28Z"
                                                    fill="#EB001B"/>
                                                <path
                                                    d="M22.5 7.5a7.5 7.5 0 1 1-10.1-7.06 7.486 7.486 0 0 1 0 11.28A7.5 7.5 0 0 1 22.5 7.5Z"
                                                    fill="#F79E1B"/>
                                            </svg>
                                        </div>

                                        <div
                                            class="flex items-center justify-center h-10 w-16 p-1 bg-white border border-[#D4AF37] rounded-sm shadow-sm">
                                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                                 class="w-full h-full fill-[#003087]">
                                                <path
                                                    d="M20.067 8.478c-.492-3.996-3.565-5.32-7.56-5.32h-5.26a1.18 1.18 0 0 0-1.163.98L3.25 21.082a.394.394 0 0 0 .388.46h3.407c.594 0 1.096-.43 1.186-1.01l1.03-6.505a1.18 1.18 0 0 1 1.164-.98h1.618c3.518 0 6.223-1.42 6.84-5.597.168-1.144.11-2.25-.262-3.15-.34-.816-.92-1.455-1.554-1.822z"
                                                    class="fill-[#D4AF37]"/>
                                                <path
                                                    d="M18.895 8.943c-.42 2.883-2.618 4.606-5.895 4.606h-1.617a1.18 1.18 0 0 0-1.164.98l-1.03 6.504c-.09.58.412 1.01 1.006 1.01h2.956c.595 0 1.096-.43 1.186-1.01l.46-2.903a1.18 1.18 0 0 1 1.163-.98h.834c2.882 0 5.105-1.164 5.61-4.605.156-1.066.02-2.07-.34-2.887-.365-.828-.96-1.464-1.55-1.82z"
                                                    class="fill-[#D4AF37]"/>
                                                <path
                                                    d="M14.945 3.158H9.686a1.18 1.18 0 0 0-1.164.98L5.69 22.082a.394.394 0 0 0 .388.46h3.406c.595 0 1.097-.43 1.187-1.01l1.03-6.505a1.18 1.18 0 0 1 1.163-.98h1.618c3.155 0 5.568-1.218 6.136-4.68.164-1.002.1-1.956-.205-2.73-.34-.818-.92-1.456-1.555-1.823-.493-3.996-3.566-5.32-7.56-5.32z"
                                                    class="fill-[#D4AF37]"/>
                                            </svg>
                                        </div>
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
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-white border border-gray-100 p-8 sticky top-8 rounded-sm shadow-sm">
                        <h3 class="text-xl font-serif text-black mb-6 border-b border-gray-100 pb-4">{{ __('Tu Selección') }}
                            <span class="text-[#D4AF37]">({{ count($cartItems) }})</span></h3>

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
                                            <p class="text-sm font-bold text-black group-hover:text-[#D4AF37] transition-colors line-clamp-1"
                                               title="{{ $item['title'] ?? 'Título desconocido' }}">
                                                {{ $item['title'] ?? 'Título desconocido' }}
                                            </p>
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

                            <div class="flex justify-between text-sm text-gray-600 items-center">
                                <span>Envío Premium</span>
                                @if($shipping == 0)
                                    <span
                                        class="text-[#D4AF37] text-xs border border-[#D4AF37] px-2 py-0.5 rounded-sm uppercase tracking-widest">Gratis</span>
                                @else
                                    <span
                                        class="text-black font-bold">{{ number_format($shipping, 2, ',', '.') }}€</span>
                                @endif
                            </div>

                            <div
                                class="flex justify-between text-3xl font-serif text-black pt-5 border-t border-gray-100 mt-5">
                                <span class="font-normal">{{ __('Total') }}</span>
                                <span class="text-black font-serif">{{ number_format($total, 2, ',', '.') }}€</span>
                            </div>
                        </div>

                        <button type="submit" id="submit-button"
                                class="w-full py-4 bg-[#D4AF37] text-white font-black uppercase tracking-[0.2em] text-sm hover:bg-gray-900 transition-all duration-300 shadow-yellow-900/10 shadow-lg">
                            {{ __('Finalizar y Pagar') }} {{ number_format($total, 2, ',', '.') }}€
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ env("STRIPE_KEY") }}');
        const elements = stripe.elements();

        // Guardamos el total para restaurarlo en el botón si hay error
        const totalAmountText = "{{ number_format($total, 2, ',', '.') }}€";

        const style = {
            base: {
                color: '#000000',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '15px',
                iconColor: '#D4AF37',
                '::placeholder': {color: '#aab7c4'}
            },
            invalid: {
                color: '#ef4444',
                iconColor: '#ef4444'
            }
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
            submitButton.textContent = 'AUTORIZANDO...';
            submitButton.classList.add('opacity-70', 'cursor-not-allowed');

            const {token, error} = await stripe.createToken(cardNumberElement);

            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                submitButton.disabled = false;
                submitButton.textContent = 'FINALIZAR Y PAGAR ' + totalAmountText;
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
