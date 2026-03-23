<x-layouts.admin>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Nuevo Libro en Lectio</h1>

        <form action="{{ route('admin.books.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div class="bg-white p-6 rounded shadow">
                <h2 class="font-bold mb-4 border-b">Datos Generales</h2>
                <div class="space-y-4">
                    <input type="text" name="title" placeholder="Título" class="w-full border p-2 rounded" required>
                    <input type="text" name="author" placeholder="Autor" class="w-full border p-2 rounded" required>
                    <input type="text" name="image_url" placeholder="URL Imagen (img/ejemplo.jpg)" class="w-full border p-2 rounded" required>
                </div>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h2 class="font-bold mb-4 border-b">Precios y Stock</h2>
                @foreach(['Tapa dura', 'E-book', 'Audiolibro'] as $tipo)
                    <div class="mb-4 p-3 bg-gray-50 rounded">
                        <p class="font-semibold text-orange-600">{{ $tipo }}</p>
                        <div class="flex gap-2">
                            <input type="number" step="0.01" name="formats[{{ $tipo }}][price]" placeholder="Precio €" class="w-1/2 border p-1" required>
                            @if($tipo === 'Tapa dura')
                                <input type="number" name="formats[{{ $tipo }}][stock]" placeholder="Stock" class="w-1/2 border p-1" required>
                            @else
                                <div class="w-1/2 text-xs text-gray-400 self-center italic">Digital (Sin stock)</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="md:col-span-2 bg-orange-600 text-white font-bold py-3 rounded hover:bg-orange-700">
                Añadir al Catálogo Global
            </button>
        </form>
    </div>
</x-layouts.admin>
