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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Escuchamos a TODOS los formularios que tengan la clase 'wishlist-form'
        const wishForms = document.querySelectorAll('.wishlist-form');

        wishForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Evita que la página se recargue

                const url = this.action;
                const token = this.querySelector('input[name="_token"]').value;
                const btn = this.querySelector('button');
                const icon = btn.querySelector('svg');

                // Animación al pulsar (opcional)
                btn.classList.add('scale-90');
                setTimeout(() => btn.classList.remove('scale-90'), 150);

                // Enviamos los datos en segundo plano
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({})
                })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            // Cambiamos el color del corazón según la respuesta
                            if(data.is_wished) {
                                icon.classList.remove('text-gray-400');
                                icon.classList.add('text-red-500', 'fill-current');
                            } else {
                                icon.classList.remove('text-red-500', 'fill-current');
                                icon.classList.add('text-gray-400');
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
</body>
</html>
