<x-layouts.admin>
    <div class="max-w-5xl mx-auto p-6 space-y-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b-2 border-[#D4AF37] pb-6 gap-4">
            <div>
                <h1 class="text-4xl font-serif font-bold text-black uppercase tracking-widest">{{ __('Editar y Ofertar') }}</h1>
                <p class="text-gray-500 font-medium mt-1 italic">{{ __('Gestionando:') }} <span class="text-black font-bold">{{ $book->title }}</span></p>
            </div>
        </div>

        <form action="{{ route('admin.books.update', $book->id) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf @method('PUT')

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-[#D4AF37]"></div>
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-6">🖋️ {{ __('Información de la Obra') }}</h2>
                    <div class="space-y-6">
                        <input type="text" name="title" value="{{ old('title', $book->title) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold outline-none focus:border-[#D4AF37]" required>
                        <input type="text" name="author" value="{{ old('author', $book->author) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold outline-none focus:border-[#D4AF37]" required>
                        <textarea name="description" rows="5" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm outline-none focus:border-[#D4AF37]">{{ old('description', $book->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-black p-8 rounded-2xl shadow-xl border border-[#D4AF37]/30">
                    <h2 class="text-xs font-black text-[#D4AF37] uppercase tracking-[0.3em] mb-6">💰 {{ __('Precios y Ofertas') }}</h2>

                    <div class="mb-8 pb-6 border-b border-gray-800">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">{{ __('Aplicar Descuento (%)') }}</label>
                        @php
                            // Extraemos el número del string "-20%"
                            $currentPercent = $book->discount_percent ? abs(intval($book->discount_percent)) : 0;
                        @endphp
                        <input type="number" name="discount_percentage" value="{{ $currentPercent }}" min="0" max="99"
                               class="w-full bg-zinc-900 border border-gray-700 rounded-xl p-3 text-lg font-black text-red-500 outline-none focus:border-red-500 transition-all text-center"
                               placeholder="0">
                        <p class="text-[9px] text-gray-500 mt-2 italic text-center">{{ __('Pon 0 para eliminar ofertas activas') }}</p>
                    </div>

                    <div class="space-y-4">
                        @foreach(['Tapa dura', 'E-book', 'Audiolibro'] as $tipo)
                            @php
                                $f = $book->formats->where('type', $tipo)->first();
                                // MATEMÁTICAS INVERSAS: Si el precio es 16€ y el descuento 20%, mostramos 20€ (el original)
                                $originalPrice = ($currentPercent > 0 && $f) ? ($f->price / (1 - ($currentPercent / 100))) : ($f->price ?? 0);
                            @endphp
                            <div class="p-4 bg-zinc-900 rounded-xl border border-gray-800">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">{{ $tipo }}</span>
                                <div class="flex items-center gap-2">
                                    <input type="number" step="0.01" name="formats[{{ $tipo }}][price]"
                                           value="{{ round($originalPrice, 2) }}"
                                           class="w-full bg-black border border-gray-700 rounded-lg p-2 text-sm font-bold text-white focus:border-[#D4AF37] outline-none">
                                    <span class="text-gray-600 font-bold">€</span>
                                </div>
                                <input type="hidden" name="formats[{{ $tipo }}][stock]" value="{{ $f->stock ?? 0 }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#D4AF37] text-black font-black py-4 rounded-2xl shadow-xl hover:bg-[#B8962E] transition-all uppercase tracking-[0.2em] text-xs">
                    {{ __('Guardar Cambios') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
