<x-layouts.admin>
    <div class="max-w-5xl mx-auto p-6 space-y-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b-2 border-[#D4AF37] pb-6 gap-4">
            <div>
                <h1 class="text-4xl font-serif font-bold text-black uppercase tracking-widest">{{ __('Añadir Obra') }}</h1>
                <p class="text-gray-500 font-medium mt-1 italic">{{ __('Incorpora un nuevo título al catálogo global de Lectio') }}</p>
            </div>
            <a href="{{ route('admin.inventory') }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                {{ __('Volver al Inventario') }}
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm">
                <p class="font-bold text-xs uppercase tracking-widest mb-2">{{ __('Revisa los campos obligatorios:') }}</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.books.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-[#D4AF37]"></div>
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                        <span class="text-[#D4AF37]">🖋️</span> {{ __('Detalles de la Publicación') }}
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">{{ __('Título del Libro') }}</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Ej: La insoportable levedad del ser"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold text-black focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37] outline-none transition-all" required>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">{{ __('Autor / Escritor') }}</label>
                            <input type="text" name="author" value="{{ old('author') }}" placeholder="Ej: Milan Kundera"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold text-black focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37] outline-none transition-all" required>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">{{ __('Portada (Ruta de imagen)') }}</label>
                            <input type="text" name="image_url" value="{{ old('image_url') }}" placeholder="Ej: img/libro2.png"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-mono text-gray-600 focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37] outline-none transition-all" required>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest block mb-2">{{ __('Sinopsis Editorial') }}</label>
                            <textarea name="description" rows="5" placeholder="Escribe un breve resumen de la obra..."
                                      class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm text-gray-700 focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37] outline-none transition-all">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                        <span class="text-[#D4AF37]">💰</span> {{ __('Formatos y Precios') }}
                    </h2>

                    <div class="space-y-4">
                        {{-- Unificamos todos los formatos para que muestren Ilimitado --}}
                        @foreach(['Tapa dura', 'E-book', 'Audiolibro'] as $tipo)
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-[#D4AF37]/50 transition-colors">
                                <span class="text-[10px] font-black text-black uppercase tracking-widest block mb-3">{{ $tipo }}</span>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-[9px] text-gray-400 uppercase font-bold">{{ __('Precio (€)') }}</label>
                                        <input type="number" step="0.01" name="formats[{{ $tipo }}][price]" placeholder="0.00"
                                               class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs font-bold text-black focus:border-[#D4AF37] outline-none" required>
                                    </div>
                                    <div class="flex items-end pb-2">
                                        <span class="text-[9px] text-gray-400 italic text-center w-full uppercase font-black">{{ __('Ilimitado') }}</span>
                                        {{-- El stock se envía como 0 de forma interna --}}
                                        <input type="hidden" name="formats[{{ $tipo }}][stock]" value="0">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-black text-[#D4AF37] font-bold py-4 rounded-2xl shadow-xl hover:bg-gray-900 active:scale-95 transition-all uppercase tracking-[0.2em] text-xs border border-[#D4AF37]/20">
                    {{ __('Publicar en Lectio') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
