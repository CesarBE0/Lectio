<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Lectio' }}</title>
    <link rel="icon" href="{{ asset('img/logo.webp') }}" type="image/webp">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-brand-bg text-gray-800 flex flex-col min-h-screen font-sans">
<x-layouts.nav />
<main class="flex-grow">
    {{ $slot }}
</main>
<x-layouts.footer />

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000, // Desaparece en 3 segundos
        timerProgressBar: true,
        background: '#ffffff', // Fondo blanco
        color: '#000000', // Letra negra
        iconColor: '#D4AF37', // Icono dorado Lectio
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Si el controlador manda un mensaje de éxito
    @if(session('success'))
    Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}'
    });
    @endif

    // Si el controlador manda un mensaje de error
    @if(session('error'))
    Toast.fire({
        icon: 'error',
        title: '{{ session('error') }}',
        iconColor: '#ef4444' // Rojo para errores
    });
    @endif
</script>
</body>
</body>
</html>
