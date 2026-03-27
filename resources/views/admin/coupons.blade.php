<x-layouts.admin>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-serif font-bold text-black mb-8 border-b-2 border-[#D4AF37] pb-4">{{ __('Marketing y Cupones') }}</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">🎟️ Crear Cupón</h2>
                    <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-xs uppercase font-black text-gray-500">Código Promocional</label>
                            <input type="text" name="code" placeholder="Ej: VERANO20" class="w-full mt-1 border border-gray-300 rounded p-2 uppercase font-mono" required>
                        </div>
                        <div>
                            <label class="text-xs uppercase font-black text-gray-500">Descuento (%)</label>
                            <input type="number" name="discount_percentage" min="1" max="100" placeholder="Ej: 15" class="w-full mt-1 border border-gray-300 rounded p-2" required>
                        </div>
                        <button type="submit" class="w-full bg-black text-[#D4AF37] font-bold py-3 rounded hover:bg-gray-900 transition mt-4">Generar Cupón</button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-500 text-[10px] uppercase tracking-widest">
                        <tr>
                            <th class="p-4 font-black">Código</th>
                            <th class="p-4 font-black">Descuento</th>
                            <th class="p-4 font-black text-center">Estado</th>
                            <th class="p-4 font-black text-right">Acción</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse($coupons ?? [] as $coupon)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4"><span class="font-mono font-bold text-black bg-gray-100 px-2 py-1 rounded">{{ $coupon->code }}</span></td>
                                <td class="p-4"><span class="text-[#D4AF37] font-black text-lg">-{{ $coupon->discount_percentage }}%</span></td>
                                <td class="p-4 text-center">
                                    @if($coupon->is_active)
                                        <span class="bg-green-100 text-green-700 text-[10px] px-2 py-1 rounded-full font-bold uppercase">Activo</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 text-[10px] px-2 py-1 rounded-full font-bold uppercase">Apagado</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="text-xs font-bold underline text-gray-500 hover:text-black">
                                            {{ $coupon->is_active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="p-8 text-center text-gray-500 italic">No hay cupones creados aún.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
