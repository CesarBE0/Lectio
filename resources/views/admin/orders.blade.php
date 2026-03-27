<x-layouts.admin>
    <div class="max-w-7xl mx-auto p-6 space-y-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b-2 border-[#D4AF37] pb-4 gap-4">
            <div>
                <h1 class="text-3xl font-serif font-bold text-black">{{ __('Gestión de Pedidos') }}</h1>
                </div>

            <div class="flex items-center gap-2">
                <label class="text-[10px] uppercase font-black text-gray-400">Filtrar:</label>
                <select id="status-filter" onchange="cambiarFiltro(this.value)" class="bg-black text-[#D4AF37] text-xs font-bold py-2 px-4 rounded-lg border border-[#D4AF37]/30 outline-none">
                    <option value="todos" {{ ($statusFilter ?? 'todos') == 'todos' ? 'selected' : '' }}>Todos los pedidos</option>
                    <option value="preparando" {{ ($statusFilter ?? '') == 'preparando' ? 'selected' : '' }}>Preparando</option>
                    <option value="de_camino" {{ ($statusFilter ?? '') == 'de_camino' ? 'selected' : '' }}>De camino</option>
                    <option value="entregado" {{ ($statusFilter ?? '') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black text-[#D4AF37]">
                <tr>
                    <th class="p-4 uppercase text-[10px] font-black tracking-widest">Referencia</th>
                    <th class="p-4 uppercase text-[10px] font-black tracking-widest">Cliente</th>
                    <th class="p-4 uppercase text-[10px] font-black tracking-widest">Importe</th>
                    <th class="p-4 uppercase text-[10px] font-black tracking-widest">Estado Temporal</th>
                    <th class="p-4 uppercase text-[10px] font-black text-right">Acciones</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="p-4"><span class="text-black font-bold text-sm">{{ $order->order_number }}</span></td>
                        <td class="p-4"><p class="text-sm font-bold text-gray-900">{{ $order->user_name }}</p></td>
                        <td class="p-4"><span class="text-sm font-black text-black">{{ number_format($order->totalPrice, 2) }}€</span></td>
                        <td class="p-4">
                            @php
                                $colors = [
                                    'preparando' => 'bg-orange-100 text-orange-700 border-orange-200',
                                    'de_camino'  => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'entregado'  => 'bg-green-100 text-green-700 border-green-200'
                                ];
                                $label = ['preparando' => 'Preparando', 'de_camino' => 'De camino', 'entregado' => 'Entregado'];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-[9px] font-black uppercase border {{ $colors[$order->status] ?? 'bg-gray-50' }}">
                                {{ $label[$order->status] ?? 'Pendiente' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-4">
                                <button onclick="openOrderModal('{{ $order->order_number }}')" class="text-black hover:text-[#D4AF37] font-bold text-xs uppercase underline underline-offset-4 decoration-[#D4AF37]">
                                    Ver Detalles
                                </button>
                                <form action="{{ route('admin.orders.destroy', $order->order_number) }}" method="POST" class="form-eliminar m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-16 text-center text-gray-400 italic">No hay pedidos registrados.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->appends(['status' => $statusFilter])->links() }}
        </div>
    </div>

    <div id="orderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" onclick="closeModal()"></div>
            <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full border-t-8 border-[#D4AF37]">
                <div class="bg-black px-6 py-4 flex justify-between items-center">
                    <h3 class="text-[#D4AF37] font-serif font-bold uppercase tracking-widest text-sm">Resumen del Pedido</h3>
                    <button onclick="closeModal()" class="text-white hover:text-[#D4AF37]">✕</button>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-black">Cliente</p>
                            <p id="modal-cliente" class="text-sm font-bold text-black">-</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 uppercase font-black">Estado Actual</p>
                            <span id="modal-status-badge" class="px-2 py-1 rounded-full text-[9px] font-black uppercase border inline-block mt-1"></span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-black mb-1">Dirección de Envío</p>
                        <p id="modal-direccion" class="text-xs text-black font-medium">-</p>
                    </div>

                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black mb-2 border-b pb-1">Libros Adquiridos</p>
                        <ul id="modal-items" class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar"></ul>
                    </div>

                    <div class="pt-4 flex justify-between items-center border-t-2 border-black">
                        <span class="text-lg font-serif font-bold text-black italic">Total Cobrado</span>
                        <span id="modal-total" class="text-xl font-black text-black">-</span>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 text-right">
                    <button type="button" onclick="closeModal()" class="bg-black text-[#D4AF37] text-xs font-bold px-8 py-3 rounded shadow-lg hover:bg-gray-900 transition-all">
                        Cerrar Detalles
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Limpiar URL al seleccionar "Todos"
        function cambiarFiltro(status) {
            if (status === 'todos') {
                window.location.href = "{{ route('admin.orders') }}";
            } else {
                window.location.href = "{{ route('admin.orders') }}?status=" + status;
            }
        }

        function openOrderModal(orderNumber) {
            const modal = document.getElementById('orderModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            fetch(`/pedidos/${orderNumber}/detalle`)
                .then(r => r.json())
                .then(order => {
                    document.getElementById('modal-cliente').innerText = order.user.name;
                    document.getElementById('modal-total').innerText = new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(order.totalPrice);

                    // CARGAR DIRECCIÓN
                    document.getElementById('modal-direccion').innerText = `${order.address}, ${order.city}`;

                    const badge = document.getElementById('modal-status-badge');
                    badge.innerText = order.status;
                    const colors = {
                        'preparando': 'bg-orange-100 text-orange-700 border-orange-200',
                        'de_camino': 'bg-blue-100 text-blue-700 border-blue-200',
                        'entregado': 'bg-green-100 text-green-700 border-green-200'
                    };
                    badge.className = `px-2 py-1 rounded-full text-[9px] font-black uppercase border inline-block mt-1 ${colors[order.status]}`;

                    const list = document.getElementById('modal-items');
                    list.innerHTML = '';
                    order.order_items.forEach(item => {
                        list.innerHTML += `<li class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-gray-100 shadow-sm text-xs">
                            <span><b>${item.book.title}</b> (${item.format_type})</span>
                            <span class="font-bold">${item.price}€</span>
                        </li>`;
                    });
                });
        }

        function closeModal() {
            document.getElementById('orderModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar pedido?',
                    text: "Se borrará del historial permanentemente.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((r) => { if (r.isConfirmed) this.submit(); });
            });
        });
    </script>
</x-layouts.admin>
