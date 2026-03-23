<x-layouts.admin>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-gray-500 text-sm font-medium">Ventas Totales</h3>
            <p class="text-3xl font-bold mt-2">€{{ number_format($stats['total_sales'], 2) }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-gray-500 text-sm font-medium">Pedidos</h3>
            <p class="text-3xl font-bold mt-2">{{ $stats['orders_count'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-gray-500 text-sm font-medium">Libros en Inventario</h3>
            <p class="text-3xl font-bold mt-2">{{ $stats['inventory_count'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-gray-500 text-sm font-medium">Clientes Registrados</h3>
            <p class="text-3xl font-bold mt-2">{{ $stats['clients_count'] }}</p>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="text-[#D4AF37]">📈</span> Ventas Mensuales
            </h3>
            <canvas id="ventasChart" height="120"></canvas>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="text-[#D4AF37]">📊</span> Distribución de Ventas por Formato
            </h3>
            <canvas id="formatosChart" height="120"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Líneas (Ventas)
        new Chart(document.getElementById('ventasChart'), {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Euros (€)',
                    data: @json(array_slice($chartVentasData, 0, 6)), // Solo primeros 6 meses como en tu foto
                    borderColor: '#000000',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });

        // Gráfico de Barras (Formatos en Pedidos)
        new Chart(document.getElementById('formatosChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($formatStats->pluck('type')) !!},
                datasets: [{
                    data: {!! json_encode($formatStats->pluck('total')) !!},
                    backgroundColor: '#D4AF37', // Dorado para las barras como en tu foto (que era naranja)
                    borderRadius: 4
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, grid: { display: false } } }
            }
        });
    </script>
</x-layouts.admin>
