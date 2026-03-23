<x-layouts.admin>
    <div class="max-w-7xl mx-auto p-6 space-y-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b-2 border-[#D4AF37] pb-4 gap-4">
            <div>
                <h1 class="text-3xl font-serif font-bold text-black">{{ __('Gestión de Pedidos') }}</h1>
                <p class="text-xs text-gray-500 italic">{{ __('Administra las ventas y el seguimiento de envíos') }}</p>
            </div>

            <form action="{{ route('admin.orders') }}" method="GET" class="flex items-center gap-2">
                <label class="text-[10px] uppercase font-black text-gray-400">{{ __('Filtrar por:') }}</label>
                <select name="status" onchange="this.form.submit()" class="bg-black text-[#D4AF37] text-xs font-bold py-2 px-4 rounded-lg border border-[#D4AF37]/30 outline-none focus:ring-2 focus:ring-[#D4AF37]/50 transition-all">
                    <option value="todos" {{ ($status ?? 'todos') == 'todos' ? 'selected' : '' }}>Todos los pedidos</option>
                    <option value="completed" {{ ($status ?? '') == 'completed' ? 'selected' : '' }}>Completados</option>
                    <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                    <option value="cancelled" {{ ($status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelados</option>
                </select>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black text-[#D4AF37]">
                <tr>
                    <th class="p-4 uppercase text-[10px] font-black tracking-widest">{{ __('Referencia / Tracking') }}</th>
                    <th class="p-4 uppercase text-[10px] font-black tracking-widest">{{ __('Cliente') }}</th>
                    <th class="p-4 uppercase text-[10px] font-black tracking-widest">{{ __('Importe') }}</th>
                    <th class="p-4 uppercase text-[10px] font-black tracking-widest">{{ __('Estado') }}</th>
                    <th class="p-4 uppercase text-[10px] font-black tracking-widest text-right">{{ __('Acciones') }}</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="text-black font-bold text-sm">#ORD-{{ $order->id }}</span>
                                <span class="text-[10px] font-mono text-[#D4AF37] font-bold bg-[#D4AF37]/5 px-1 rounded w-fit">
                                        {{ $order->trackingNumber ?? 'SIN TRACKING' }}
                                    </span>
                            </div>
                        </td>
                        <td class="p-4">
                            <p class="text-sm font-bold text-gray-900">{{ $order->user->name ?? 'Invitado' }}</p>
                            <p class="text-[10px] text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="p-4">
                            <span class="text-sm font-black text-black">{{ number_format($order->totalPrice, 2) }}€</span>
                        </td>
                        <td class="p-4">
                            @php
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-700 border-green-200',
                                    'pending' => 'bg-gray-100 text-gray-600 border-gray-200',
                                    'cancelled' => 'bg-red-100 text-red-700 border-red-200'
                                ];
                                $currentStyle = $statusColors[$order->status] ?? 'bg-gray-50 text-gray-400';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-[9px] font-black uppercase border {{ $currentStyle }}">
                                    {{ __($order->status) }}
                                </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end items-center gap-4">
                                <button onclick="openOrderModal({{ $order->id }})"
                                        class="text-black hover:text-[#D4AF37] font-bold text-xs uppercase tracking-tighter transition-colors underline decoration-[#D4AF37]/30 decoration-2 underline-offset-4">
                                    {{ __('Ver Detalles') }}
                                </button>

                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="form-eliminar m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs uppercase tracking-tighter transition-colors">
                                        {{ __('Eliminar') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-16 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-4xl">📄</span>
                                <p class="text-gray-400 italic text-sm">{{ __('No se han encontrado pedidos con este criterio.') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->appends(['status' => $status])->links() }}
        </div>
    </div>

    <div id="orderModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-t-8 border-[#D4AF37]">

                <div class="bg-black px-6 py-4 flex justify-between items-center">
                    <h3 class="text-[#D4AF37] font-serif font-bold uppercase tracking-widest text-sm">{{ __('Resumen del Pedido') }}</h3>
                    <button onclick="closeModal()" class="text-white hover:text-[#D4AF37] transition-colors text-xl">✕</button>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="border-b border-gray-100 pb-2">
                            <p class="text-[10px] text-gray-400 uppercase font-black">{{ __('Cliente') }}</p>
                            <p id="modal-cliente" class="text-sm font-bold text-black">-</p>
                        </div>
                        <div class="border-b border-gray-100 pb-2 text-right">
                            <p class="text-[10px] text-gray-400 uppercase font-black">{{ __('Nº Seguimiento') }}</p>
                            <p id="modal-tracking" class="text-xs font-mono text-[#D4AF37] font-bold">-</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black mb-3 border-b border-gray-50 pb-1">{{ __('Artículos Comprados') }}</p>
                        <ul id="modal-items" class="space-y-3 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                        </ul>
                    </div>

                    <div class="pt-4 flex justify-between items-center border-t-2 border-black">
                        <span class="text-xl font-serif font-bold text-black italic">{{ __('Total Final') }}</span>
                        <span id="modal-total" class="text-2xl font-black text-black">-</span>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 text-right">
                    <button onclick="closeModal()" class="bg-black text-[#D4AF37] text-xs font-bold px-8 py-3 rounded-lg hover:bg-gray-900 transition-all active:scale-95 shadow-lg">
                        {{ __('Entendido, Cerrar') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Función para abrir el modal y cargar datos vía AJAX
        function openOrderModal(orderId) {
            const modal = document.getElementById('orderModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Evita scroll de fondo

            const itemsList = document.getElementById('modal-items');
            itemsList.innerHTML = '<li class="text-xs text-gray-400 italic animate-pulse">Consultando archivos de Lectio...</li>';

            // Llamada a la ruta en español que configuramos
            fetch(`/pedidos/${orderId}/detalle`)
                .then(response => response.json())
                .then(order => {
                    document.getElementById('modal-cliente').innerText = order.user ? order.user.name : 'Invitado';
                    document.getElementById('modal-tracking').innerText = order.trackingNumber || 'PENDIENTE GENERAR';
                    document.getElementById('modal-total').innerText = new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(order.totalPrice);

                    itemsList.innerHTML = '';

                    // Recorremos los artículos del pedido
                    order.order_items.forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'flex justify-between items-center bg-gray-50 p-3 rounded-xl border-l-4 border-[#D4AF37] shadow-sm';
                        li.innerHTML = `
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900 text-xs">${item.book.title}</span>
                                <span class="text-[9px] text-[#D4AF37] font-black uppercase tracking-tighter">${item.format_type}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-black">${item.quantity} x ${item.price}€</span>
                            </div>
                        `;
                        itemsList.appendChild(li);
                    });
                })
                .catch(error => {
                    itemsList.innerHTML = '<li class="text-red-500 text-xs">Error al cargar los detalles. Inténtalo de nuevo.</li>';
                });
        }

        // Función para cerrar el modal
        function closeModal() {
            document.getElementById('orderModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Alerta de confirmación para eliminar (SweetAlert2)
        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar registro?',
                    text: "Esta acción es irreversible y borrará el historial del pedido.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000000',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, borrar',
                    cancelButtonText: 'Cancelar',
                    didOpen: () => {
                        Swal.getConfirmButton().style.color = '#D4AF37';
                    }
                }).then((result) => { if (result.isConfirmed) this.submit(); });
            });
        });
    </script>

    <style>
        /* Estilo para el scroll del modal */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 10px; }
    </style>
</x-layouts.admin>
