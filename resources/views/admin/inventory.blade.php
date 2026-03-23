<x-layouts.admin>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex justify-between items-center mb-8 border-b-2 border-[#D4AF37] pb-4">
            <h1 class="text-3xl font-serif font-bold text-black">{{ __('Panel de Inventario') }}</h1>

            <a href="{{ route('admin.books.create') }}"
               class="bg-black hover:bg-gray-800 text-[#D4AF37] font-bold py-2 px-6 rounded shadow-md transition-all border border-[#D4AF37]/20">
                + {{ __('Nuevo Libro') }}
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black text-[#D4AF37]">
                <tr>
                    <th class="p-4 uppercase text-xs font-black">{{ __('Libro') }}</th>
                    <th class="p-4 uppercase text-xs font-black">{{ __('Formatos y Precios') }}</th>
                    <th class="p-4 uppercase text-xs font-black text-right">{{ __('Acciones') }}</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach($books as $book)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($book->image_url) }}" class="w-12 h-16 object-cover rounded shadow-sm">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $book->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $book->author }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($book->formats as $f)
                                    <span class="text-[10px] px-2 py-1 bg-gray-100 border border-gray-200 rounded text-gray-700">
                                        <strong class="text-black">{{ $f->type }}:</strong> {{ $f->price }}€
                                        @if($f->type == 'Tapa dura')
                                            <span class="text-[#D4AF37] ml-1 font-bold">({{ $f->stock }} ud)</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end items-center gap-6">
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="text-black hover:text-[#D4AF37] font-bold text-sm transition-colors">
                                    {{ __('Editar') }}
                                </a>

                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="form-eliminar m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-sm transition-colors">
                                        {{ __('Eliminar') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
                    confirmButtonColor: '#000000', // Botón de confirmar en Negro
                    cancelButtonColor: '#d33',    // Botón de cancelar en Rojo
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    didOpen: () => {
                        const confirmBtn = Swal.getConfirmButton();
                        confirmBtn.style.color = '#D4AF37'; // Texto Dorado en el botón Negro
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
