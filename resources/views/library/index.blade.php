<x-layouts.layout title="{{ __('Mi Biblioteca Personal') }} - Lectio">

    <div class="bg-gray-50 min-h-screen py-12">
        <div class="container mx-auto px-4 md:px-6">

            <div
                class="flex flex-col md:flex-row items-start md:items-center justify-between mb-10 border-b-2 border-[#D4AF37] pb-6 gap-4">
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
                <div
                    class="card bg-white shadow-sm border border-gray-100 hover:border-[#D4AF37]/30 transition-all duration-300 group">
                    <div class="card-body p-4 md:p-6 flex flex-row items-center gap-4">
                        <div
                            class="text-3xl md:text-4xl bg-gray-50 p-2 md:p-3 rounded-lg group-hover:scale-110 transition-transform">
                            📚
                        </div>
                        <div>
                            <span class="text-2xl md:text-4xl font-black text-black block">{{ $stats->total }}</span>
                            <span
                                class="text-[10px] md:text-xs text-gray-400 uppercase font-black tracking-widest">{{ __('Libros Totales') }}</span>
                        </div>
                    </div>
                </div>
                <div
                    class="card bg-white shadow-sm border border-gray-100 hover:border-[#D4AF37]/30 transition-all duration-300 group">
                    <div class="card-body p-4 md:p-6 flex flex-row items-center gap-4">
                        <div
                            class="text-3xl md:text-4xl bg-gray-50 p-2 md:p-3 rounded-lg group-hover:scale-110 transition-transform">
                            ⭐
                        </div>
                        <div>
                            <span id="favorites-count"
                                  class="text-2xl md:text-4xl font-black text-black block">{{ $stats->favorites }}</span>
                            <span
                                class="text-[10px] md:text-xs text-gray-400 uppercase font-black tracking-widest">{{ __('Favoritos') }}</span>
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
                    <div
                        class="card bg-white shadow-sm border border-gray-100 h-full group flex flex-col relative overflow-hidden transition hover:shadow-xl hover:border-[#D4AF37]/20">

                        <button onclick="toggleFavorite(this, {{ $book->id }})"
                                class="favorite-btn absolute top-3 right-3 z-20 w-9 h-9 flex items-center justify-center bg-white rounded-full shadow-md transition-all hover:scale-105 active:scale-90"
                                data-favorite="{{ $book->pivot->is_favorite ? 'true' : 'false' }}">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5"
                                 class="heart-icon h-5 w-5 transition-colors duration-300 {{ $book->pivot->is_favorite ? 'fill-red-500 stroke-red-500' : 'fill-none stroke-gray-400' }}">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                            </svg>

                        </button>

                        <figure
                            class="px-4 pt-4 h-64 bg-gray-50 flex items-center justify-center border-b border-gray-100">
                            <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}"
                                 class="h-full object-contain drop-shadow-lg transform group-hover:scale-105 transition duration-300"/>
                        </figure>

                        <div class="card-body p-5 flex-grow flex flex-col">
                            <h2 class="card-title text-lg leading-tight font-serif font-bold line-clamp-2 text-black mb-1">{{ $book->title }}</h2>
                            <p class="text-gray-500 text-sm mb-4">{{ $book->author }}</p>

                            <div class="mb-6">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-black text-[#D4AF37] text-[10px] uppercase font-black tracking-widest rounded-md border border-[#D4AF37]/30 shadow-sm">
                                    {{ $book->pivot->format ?? 'Tapa dura' }}
                                </span>
                            </div>

                            <div class="w-full mt-auto">
                                @php
                                    $formato = strtolower($book->pivot->format ?? 'físico');
                                    $fechaCompra = $book->pivot->created_at ?? now();
                                    $horasTranscurridas = $fechaCompra->diffInHours(now());

                                    if($horasTranscurridas < 48) {
                                        $estadoEnvio = 'Preparando tu pedido 📦';
                                    } elseif($horasTranscurridas < 96) {
                                        $estadoEnvio = 'De camino 🚚';
                                    } else {
                                        $estadoEnvio = 'Entregado ✅';
                                    }

                                    $numeroPedido = $book->pivot->order_number ?? 'LCT-00000000';
                                @endphp

                                <div>
                                    @if(str_contains($formato, 'tapa dura') || str_contains($formato, 'físico'))
                                        <button onclick="openShippingModal({{ $book->id }})"
                                                class="w-full py-2 bg-black text-white text-xs font-bold uppercase tracking-widest hover:bg-[#D4AF37] transition-colors text-center block rounded-md">
                                            Detalles de Envío
                                        </button>

                                        <div id="modal-shipping-{{ $book->id }}"
                                             class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
                                            <div
                                                class="bg-white p-8 max-w-sm w-full rounded-xl shadow-2xl relative text-left">
                                                <button onclick="closeShippingModal({{ $book->id }})"
                                                        class="absolute top-4 right-5 text-2xl text-gray-400 hover:text-black">
                                                    &times;
                                                </button>
                                                <h3 class="text-xl font-serif text-black mb-4 border-b pb-3 font-bold">
                                                    Estado del Pedido</h3>
                                                <div class="space-y-4 text-sm mt-2">
                                                    <p><span
                                                            class="text-gray-500 font-bold text-xs uppercase block mb-1">Nº Pedido:</span>
                                                        <span class="text-black">{{ $numeroPedido }}</span></p>
                                                    <p><span
                                                            class="text-gray-500 font-bold text-xs uppercase block mb-1">Dirección de entrega:</span>
                                                        <span class="text-black">{{ $book->pivot->address ?? 'No registrada' }}, {{ $book->pivot->city ?? '' }}</span>
                                                    </p>
                                                    <p class="pt-2"><span
                                                            class="text-gray-500 font-bold text-xs uppercase block mb-1">Estado:</span>
                                                        <span
                                                            class="font-bold text-[#D4AF37] bg-yellow-50 px-3 py-1 rounded-md">{{ $estadoEnvio }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif(str_contains($formato, 'e-book') || str_contains($formato, 'digital'))
                                        <a href="{{ asset('pdfs/libro-' . $book->id . '.pdf') }}" target="_blank"
                                           class="w-full py-2 bg-black text-white text-xs font-bold uppercase tracking-widest hover:bg-[#D4AF37] transition-colors text-center block rounded-md">
                                            Leer
                                        </a>

                                    @elseif(str_contains($formato, 'audio'))
                                        <button onclick="toggleAudio({{ $book->id }})"
                                                class="w-full py-2 bg-black text-white text-xs font-bold uppercase tracking-widest hover:bg-[#D4AF37] transition-colors text-center block rounded-md">
                                            Escuchar
                                        </button>

                                        <div id="audio-container-{{ $book->id }}"
                                             class="hidden mt-3 bg-gray-50 border border-gray-200 p-2.5 rounded-md shadow-inner flex items-center gap-3 transition-all">
                                            <audio id="audio-{{ $book->id }}"
                                                   src="{{ asset('audio/libro-' . $book->id . '.mp3') }}"
                                                   preload="metadata"></audio>
                                            <button onclick="togglePlay({{ $book->id }})"
                                                    class="w-8 h-8 flex-shrink-0 flex items-center justify-center bg-black text-[#D4AF37] rounded-full hover:scale-105 transition shadow-sm">
                                                <svg id="icon-play-{{ $book->id }}" class="w-4 h-4 ml-0.5"
                                                     fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                                <svg id="icon-pause-{{ $book->id }}" class="w-4 h-4 hidden"
                                                     fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                                </svg>
                                            </button>
                                            <div
                                                class="flex-grow flex items-center text-[10px] font-bold text-gray-500 gap-2">
                                                <span id="current-time-{{ $book->id }}"
                                                      class="w-7 text-right">0:00</span>
                                                <input type="range" id="seek-{{ $book->id }}" value="0" max="100"
                                                       class="w-full h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#D4AF37]"
                                                       oninput="seekAudio({{ $book->id }}, this.value)">
                                                <span id="duration-{{ $book->id }}" class="w-7 text-left">0:00</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
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
            const countElement = document.getElementById('favorites-count');

            // 1. Cambiamos el color del corazón y el número al instante (Magia Visual)
            if (isFavorite) {
                // Si era favorito, lo quitamos
                svg.classList.remove('fill-red-500', 'stroke-red-500');
                svg.classList.add('fill-none', 'stroke-gray-300');
                button.setAttribute('data-favorite', 'false');

                // Restamos 1 al contador
                if (countElement) {
                    countElement.innerText = parseInt(countElement.innerText) - 1;
                }
            } else {
                // Si no era favorito, lo ponemos rojo
                svg.classList.remove('fill-none', 'stroke-gray-300');
                svg.classList.add('fill-red-500', 'stroke-red-500');
                button.setAttribute('data-favorite', 'true');

                // Sumamos 1 al contador
                if (countElement) {
                    countElement.innerText = parseInt(countElement.innerText) + 1;
                }
            }

            // 2. Le avisamos a la base de datos "por debajo de la mesa"
            fetch(`/biblioteca/favorito/${bookId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // Si la base de datos da un error raro, recargamos la página por seguridad
                    if (!data.success) location.reload();
                })
                .catch(error => location.reload());
        }

        function openShippingModal(id) {
            document.getElementById('modal-shipping-' + id).classList.remove('hidden');
        }

        function closeShippingModal(id) {
            document.getElementById('modal-shipping-' + id).classList.add('hidden');
        }

        function toggleAudio(id) {
            const container = document.getElementById('audio-container-' + id);
            const audio = document.getElementById('audio-' + id);

            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
                if (audio) {
                    audio.pause();
                    document.getElementById('icon-play-' + id).classList.remove('hidden');
                    document.getElementById('icon-pause-' + id).classList.add('hidden');
                }
            }
        }

        function togglePlay(id) {
            let audio = document.getElementById('audio-' + id);
            let playIcon = document.getElementById('icon-play-' + id);
            let pauseIcon = document.getElementById('icon-pause-' + id);

            document.querySelectorAll('audio').forEach(a => {
                if (a.id !== 'audio-' + id) {
                    a.pause();
                    let otherId = a.id.split('-')[1];
                    if (document.getElementById('icon-play-' + otherId)) {
                        document.getElementById('icon-play-' + otherId).classList.remove('hidden');
                        document.getElementById('icon-pause-' + otherId).classList.add('hidden');
                    }
                }
            });

            if (audio.paused) {
                audio.play();
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
            } else {
                audio.pause();
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
            }
        }

        function seekAudio(id, value) {
            let audio = document.getElementById('audio-' + id);
            if (audio.duration) {
                audio.currentTime = audio.duration * (value / 100);
            }
        }

        function formatTime(seconds) {
            if (isNaN(seconds)) return "0:00";
            let min = Math.floor(seconds / 60);
            let sec = Math.floor(seconds % 60);
            return min + ":" + (sec < 10 ? "0" + sec : sec);
        }

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('audio').forEach(audio => {
                audio.addEventListener('loadedmetadata', function () {
                    let id = this.id.split('-')[1];
                    let dur = document.getElementById('duration-' + id);
                    if (dur) dur.innerText = formatTime(this.duration);
                });

                audio.addEventListener('timeupdate', function () {
                    let id = this.id.split('-')[1];
                    let cur = document.getElementById('current-time-' + id);
                    if (cur) cur.innerText = formatTime(this.currentTime);
                    let seekSlider = document.getElementById('seek-' + id);
                    if (seekSlider && !seekSlider.matches(':active')) {
                        seekSlider.value = (this.currentTime / this.duration) * 100 || 0;
                    }
                });

                audio.addEventListener('ended', function () {
                    let id = this.id.split('-')[1];
                    let playIcon = document.getElementById('icon-play-' + id);
                    let pauseIcon = document.getElementById('icon-pause-' + id);
                    let seekSlider = document.getElementById('seek-' + id);
                    if (playIcon) playIcon.classList.remove('hidden');
                    if (pauseIcon) pauseIcon.classList.add('hidden');
                    if (seekSlider) seekSlider.value = 0;
                });
            });
        });
    </script>
</x-layouts.layout>
