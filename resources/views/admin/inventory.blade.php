<x-layouts.admin>
    <div class="max-w-7xl mx-auto p-6 space-y-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b-2 border-[#D4AF37] pb-6 gap-4">
            <div>
                <h1 class="text-4xl font-serif font-bold text-black uppercase tracking-widest">{{ __('Inventario de Libros') }}</h1>
                <p class="text-gray-500 font-medium mt-1 italic">{{ __('Gestiona el catálogo global, precios y ofertas de Lectio') }}</p>
            </div>

            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-4">
                <form action="{{ route('admin.inventory') }}" method="GET" class="flex w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Buscar por título o autor..."
                           class="w-full sm:w-64 bg-white border border-gray-200 rounded-l-xl px-4 py-2 text-sm focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37] outline-none transition-all">
                    <button type="submit" class="bg-black text-[#D4AF37] px-4 py-2 rounded-r-xl border border-black hover:bg-gray-900 transition-colors">
                        🔍
                    </button>
                </form>

                <a href="{{ route('admin.books.create') }}"
                   class="bg-black text-[#D4AF37] font-bold py-2 px-6 rounded-xl shadow-lg hover:bg-gray-900 active:scale-95 transition-all uppercase tracking-widest text-xs border border-[#D4AF37]/20 flex items-center justify-center gap-2">
                    <span class="text-lg">+</span> {{ __('Nuevo Título') }}
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black text-[#D4AF37]">
                <tr>
                    <th class="p-5 uppercase text-[10px] font-black tracking-[0.2em]">{{ __('Obra Literaria') }}</th>
                    <th class="p-5 uppercase text-[10px] font-black tracking-[0.2em]">{{ __('Precios por Formato') }}</th>
                    <th class="p-5 uppercase text-[10px] font-black tracking-[0.2em] text-right">{{ __('Acciones') }}</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                @forelse($books as $book)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="relative flex-shrink-0">
                                    <img src="{{ asset($book->image_url) }}" class="w-14 h-20 object-cover rounded shadow-md group-hover:scale-105 transition-transform">
                                    @if($book->discount_percent)
                                        <span class="absolute -top-2 -right-2 bg-red-600 text-white text-[8px] font-black px-1.5 py-0.5 rounded-full border border-white shadow-sm uppercase">
                                                {{ $book->discount_percent }}
                                            </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 flex items-center gap-1.5">
                                        {{ $book->title }}
                                        @if($book->old_price)
                                            <span class="text-[#D4AF37] text-xs" title="Oferta de Mes Activa">⚡</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 font-medium">{{ $book->author }}</p>
                                    @if($book->old_price)
                                        <p class="text-[9px] text-red-500 font-bold uppercase mt-1">
                                            {{ __('Antes:') }} {{ number_format($book->old_price, 2) }}€
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="p-5">
                            <div class="flex flex-wrap gap-2">
                                @foreach($book->formats as $f)
                                    <div class="flex flex-col bg-gray-50 border border-gray-100 rounded-lg px-3 py-1.5 min-w-[80px]">
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">{{ $f->type }}</span>
                                        <span class="text-xs font-bold text-black">{{ number_format($f->price, 2) }}€</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        <td class="p-5 text-right">
                            <div class="flex justify-end items-center gap-6">
                                <a href="{{ route('admin.books.edit', $book->id) }}"
                                   class="text-black hover:text-[#D4AF37] font-bold text-xs uppercase tracking-tighter transition-colors underline decoration-[#D4AF37]/30 decoration-2 underline-offset-4">
                                    {{ __('Editar') }}
                                </a>

                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="form-eliminar m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs uppercase tracking-tighter transition-colors">
                                        {{ __('Eliminar') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-4xl">📚</span>
                                <p class="text-gray-400 italic text-sm">{{ __('No se han encontrado libros en el catálogo.') }}</p>
                                @if(request('search'))
                                    <a href="{{ route('admin.inventory') }}" class="text-[#D4AF37] font-bold text-xs uppercase underline">{{ __('Limpiar búsqueda') }}</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $books->appends(['search' => request('search')])->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Confirmas la eliminación?',
                    text: "Esta acción retirará el libro del catálogo de Lectio permanentemente.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000000',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    didOpen: () => {
                        const confirmBtn = Swal.getConfirmButton();
                        confirmBtn.style.color = '#D4AF37';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
</x-layouts.admin>
