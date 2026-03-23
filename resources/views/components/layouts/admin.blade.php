<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lectio Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

<aside class="w-64 bg-white border-r flex flex-col justify-between">
    <div>
        <div class="h-16 flex items-center px-6 border-b">
            <span class="text-xl font-bold text-orange-600">📖 Lectio Admin</span>
        </div>
        <nav class="p-4 space-y-2 text-gray-600">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-orange-50 hover:text-orange-600">Dashboard</a>
            <a href="{{ route('admin.inventory') }}" class="block px-4 py-2 rounded hover:bg-orange-50 hover:text-orange-600">Inventario de Libros</a>
            <a href="{{ route('admin.orders') }}" class="block px-4 py-2 rounded hover:bg-orange-50 hover:text-orange-600">Gestión de Pedidos</a>
        </nav>
    </div>
    <div class="p-4 border-t">
        <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-500 hover:text-gray-800">Volver a la tienda</a>
    </div>
</aside>

<main class="flex-1 flex flex-col">
    <header class="h-16 bg-white border-b flex items-center justify-between px-6">
        <div class="text-gray-500">Panel de Control</div>
        <div class="flex items-center space-x-4">
            <span class="text-sm font-medium">{{ Auth::user()->name ?? 'Administrador' }}</span>
            <div class="w-8 h-8 bg-orange-500 rounded-full text-white flex items-center justify-center font-bold">A</div>
        </div>
    </header>

    <div class="p-6 overflow-y-auto h-full">
        {{ $slot }}
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
