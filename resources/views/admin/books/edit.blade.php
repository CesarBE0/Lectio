<x-layouts.admin>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Editar: {{ $book->title }}</h1>

        <form action="{{ route('admin.books.update', $book->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @csrf
            @method('PUT')

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold mb-4 text-orange-600 border-b pb-2">Información General</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Título</label>
                        <input type="text" name="title" value="{{ $book->title }}" class="w-full mt-1 border border-gray-300 rounded-md p-2 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Autor</label>
                        <input type="text" name="author" value="{{ $book->author }}" class="w-full mt-1 border border-gray-300 rounded-md p-2 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ruta de Imagen</label>
                        <input type="text" name="image_url" value="{{ $book->image_url }}" class="w-full mt-1 border border-gray-300 rounded-md p-2 shadow-sm" required>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold mb-4 text-orange-600 border-b pb-2">Formatos y Precios</h2>
                <div class="space-y-4">
                    @foreach(['Tapa dura', 'E-book', 'Audiolibro'] as $tipo)
                        @php $f = $book->formats->where('type', $tipo)->first(); @endphp
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="font-bold text-gray-700 block mb-2">{{ $tipo }}</span>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-500 uppercase">Precio (€)</label>
                                    <input type="number" step="0.01" name="formats[{{ $tipo }}][price]" value="{{ $f->price ?? 0.00 }}" class="w-full border rounded p-1" required>
                                </div>
                                @if($tipo === 'Tapa dura')
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase">Stock</label>
                                        <input type="number" name="formats[{{ $tipo }}][stock]" value="{{ $f->stock ?? 0 }}" class="w-full border rounded p-1" required>
                                    </div>
                                @else
                                    <input type="hidden" name="formats[{{ $tipo }}][stock]" value="0">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md">
                    Actualizar Todo
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
