<x-layouts.layout title="{{ __('Mis Pedidos') }} - Lectio">
    <div class="bg-gray-50 min-h-screen py-16 text-black font-sans">
        <div class="container mx-auto px-4 md:px-6 max-w-5xl">

            <div class="text-center mb-12 border-b border-gray-100 pb-8">
                <h1 class="text-4xl font-serif text-black uppercase tracking-[0.2em] mb-2">{{ __('Mis Pedidos') }}</h1>
                <p class="text-gray-500 text-sm uppercase tracking-widest">{{ __('Historial de tus adquisiciones en Lectio') }}</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-sm shadow-sm overflow-hidden">
                @if(count($orders) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-[10px] uppercase tracking-widest text-gray-500">
                                <th class="p-4 font-bold">{{ __('Nº Pedido') }} / {{ __('Fecha') }}</th>
                                <th class="p-4 font-bold">{{ __('Artículo') }}</th>
                                <th class="p-4 font-bold">{{ __('Formato') }}</th>
                                <th class="p-4 font-bold">{{ __('Precio') }}</th>
                                <th class="p-4 font-bold text-center">{{ __('Factura') }}</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                @php
                                    $fecha = $order->pivot->created_at ?? now();
                                    $numeroPedido = $order->pivot->order_number ?? 'LCT-00000000';
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="p-4">
                                        <span class="block text-xs font-bold text-black">{{ $numeroPedido }}</span>
                                        <span class="block text-[10px] text-gray-400 mt-1">{{ $fecha->format('d/m/Y - H:i') }}</span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-10 bg-gray-100 rounded-sm overflow-hidden">
                                                @if($order->image_url)
                                                    <img src="{{ asset($order->image_url) }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <span class="text-sm font-bold text-black line-clamp-1">{{ $order->title }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                            <span class="inline-block px-2 py-1 bg-black text-[#D4AF37] text-[9px] uppercase tracking-widest rounded-sm">
                                                {{ $order->pivot->format ?? 'Físico' }}
                                            </span>
                                    </td>
                                    <td class="p-4 text-sm font-bold text-black">
                                        {{ number_format(($order->pivot->price ?? 0) - ($order->pivot->discount ?? 0) + ($order->pivot->shipping ?? 0), 2, ',', '.') }}€
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="{{ route('orders.invoice', $order->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 hover:bg-[#D4AF37] hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="text-4xl mb-4">📜</div>
                        <h3 class="text-lg font-serif text-black mb-2">{{ __('Aún no tienes pedidos') }}</h3>
                        <p class="text-sm text-gray-500 mb-6">{{ __('Tu historial de compras está vacío.') }}</p>
                        <a href="{{ route('catalogo') }}" class="inline-block px-8 py-3 bg-black text-[#D4AF37] text-xs font-bold uppercase tracking-widest rounded-sm hover:bg-gray-900 transition-colors">
                            {{ __('Ir al catálogo') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.layout>
