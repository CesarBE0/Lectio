<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Lectio' }}</title>
    <link rel="icon" href="{{ asset('img/logo.webp') }}" type="image/webp">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-brand-bg text-gray-800 flex flex-col min-h-screen font-sans">
<x-layouts.nav />
<main class="flex-grow">
    {{ $slot }}
</main>
<x-layouts.footer />
@if(session('success'))
    <div id="toast-success" class="fixed top-24 right-5 z-50 bg-black text-[#D4AF37] px-6 py-4 rounded-lg shadow-2xl border border-[#D4AF37]/50 flex items-center gap-3 transform transition-all duration-500 translate-x-[150%]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-bold text-sm tracking-wide">{{ session('success') }}</span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast-success');
            if (toast) {
                // 1. Entra a la pantalla suavemente (quitamos la clase que lo esconde a la derecha)
                setTimeout(() => {
                    toast.classList.remove('translate-x-[150%]');
                    toast.classList.add('translate-x-0');
                }, 100); // Pequeño retardo para que la animación se note al cargar la página

                // 2. A los 3.5 segundos, se vuelve a esconder solo
                setTimeout(() => {
                    toast.classList.remove('translate-x-0');
                    toast.classList.add('translate-x-[150%]');
                }, 3500);
            }
        });
    </script>
@endif
</body>
</html>
