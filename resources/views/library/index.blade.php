<x-layouts.layout title="{{ __('Mi Biblioteca Personal') }} - Lectio">

    <div class="bg-gray-50 min-h-screen py-12">
        <div class="container mx-auto px-4 md:px-6">

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-10 border-b-2 border-[#D4AF37] pb-6 gap-4">
                <div>
                    <h1 class="text-4xl font-serif font-bold text-black uppercase tracking-widest">{{ __('Mi Biblioteca') }}</h1>
                    <p class="text-gray-500 font-medium mt-1">{{ $stats->total }} {{ __('tesoros literarios en tu colección') }}</p>
                </div>
                <a href="{{ route('catalogo') }}"
                   class="btn bg-black text-[#D4AF37] hover:bg-gray-800 border-none rounded-full px-6 font-bold shadow-md">
                    {{ __('Seguir Comprando') }}
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4 md:gap-6 mb-10">
                <div class="card bg-white shadow-sm border border-gray-100 hover:border-[#D4AF37]/30 transition-all duration-300 group">
                    <div class="card-body p-4 md:p-6 flex flex-row items-center gap-4">
                        <div class="text-3xl md:text-4xl bg-gray-50 p-2 md:p-3 rounded-lg group-hover:scale-110 transition-transform">📚</div>
                        <div>
                            <span class="text-2xl md:text-4xl font-black text-black block">{{ $stats->total }}</span>
                            <span class="text-[10px] md:text-xs text-gray-400 uppercase font-black tracking-widest">{{ __('Libros Totales') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card bg-white shadow-sm border border-gray-100 hover:border-[#D4AF37]/30 transition-all duration-300 group">
                    <div class="card-body p-4 md:p-6 flex flex-row items-center gap-4">
                        <div class="text-3xl md:text-4xl bg-gray-50 p-2 md:p-3 rounded-lg group-hover:scale-110 transition-transform">⭐</div>
                        <div>
                            <span class="text-2xl md:text-4xl font-black text-black block">{{ $stats->favorites }}</span>
                            <span class="text-[10px] md:text-xs text-gray-400 uppercase font-black tracking-widest">{{ __('Favoritos') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mb-10 overflow-x-auto pb-2">
                <a href="{{ route('library.index') }}"
                   class="px-5 py-2.5 rounded-full text-sm font-bold transition-all {{ !$filter ? 'bg-black text-[#D4AF37] shadow' : 'bg-white text-gray-700 border border-gray-200 hover:border-[#D4AF37]' }}">
                    {{ __('Todos los libros') }}
                </a>
                <a href="{{ route('library.index', ['filter' => 'favorites']) }}"
                   class="px-5 py-2.5 rounded-full text-sm font-bold transition-all {{ $filter == 'favorites' ? 'bg-black text-[#D4AF37] shadow' : 'bg-white text-gray-700 border border-gray-200 hover:border-[#D4AF37]' }}">
                    {{ __('Favoritos') }}
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                @foreach($books as $book)
                    <div class="card bg-white shadow-sm border border-gray-100 h-full group flex flex-col relative overflow-hidden transition hover:shadow-xl hover:border-[#D4AF37]/20">

                        <button onclick="toggleFavorite(this, {{ $book->id }})"
                                class="favorite-btn absolute top-3 right-3 z-20 p-2 bg-white rounded-full shadow-md transition-all active:scale-90"
                                data-favorite="{{ $book->pivot->is_favorite ? 'true' : 'false' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2"
                                 class="heart-icon h-5 w-5 transition-colors duration-300 {{ $book->pivot->is_favorite ? 'fill-red-500 stroke-red-500' : 'fill-none stroke-gray-300' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l8.78-8.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                            </svg>
                        </button>

                        <figure class="px-4 pt-4 h-64 bg-gray-50 flex items-center justify-center border-b border-gray-100">
                            <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" class="h-full object-contain drop-shadow-lg transform group-hover:scale-105 transition duration-300"/>
                        </figure>

                        <div class="card-body p-5 flex-grow flex flex-col">
                            <h2 class="card-title text-lg leading-tight font-serif font-bold line-clamp-2 text-black mb-1">{{ $book->title }}</h2>
                            <p class="text-gray-500 text-sm mb-4">{{ $book->author }}</p>

                            <div class="mb-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-black text-[#D4AF37] text-[10px] uppercase font-black tracking-widest rounded-md border border-[#D4AF37]/30 shadow-sm">
                                    {{ $book->pivot->format ?? 'Tapa dura' }}
                                </span>
                            </div>

                            <div class="w-full mt-auto">
                                <button class="w-full py-2.5 bg-black text-[#D4AF37] hover:bg-gray-800 rounded normal-case font-black text-[10px] sm:text-xs uppercase tracking-widest shadow transition-colors">
                                    {{ __('Detalles de Envío') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function toggleFavorite(button, bookId) {
            const svg = button.querySelector('.heart-icon');
            let isFavorite = button.getAttribute('data-favorite') === 'true';

            if (isFavorite) {
                svg.classList.remove('fill-red-500', 'stroke-red-500');
                svg.classList.add('fill-none', 'stroke-gray-300');
                button.setAttribute('data-favorite', 'false');
            } else {
                svg.classList.remove('fill-none', 'stroke-gray-300');
                svg.classList.add('fill-red-500', 'stroke-red-500');
                button.setAttribute('data-favorite', 'true');
            }

            fetch(`/biblioteca/favorito/${bookId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) location.reload();
                })
                .catch(error => location.reload());
        }
    </script>
</x-layouts.layout>
