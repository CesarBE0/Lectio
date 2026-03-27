<x-layouts.admin title="Bandeja de Soporte - Admin">
    <div class="p-6 sm:p-10 space-y-6">

        {{-- Cabecera del Panel --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif font-bold text-gray-900 tracking-tight">{{__("Bandeja de Soporte")}}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{__("Gestiona las consultas de tus lectores.")}}
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full ml-2">{{ $unreadCount }} {{__("nuevos")}}</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Alertas de éxito --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Tabla de Mensajes --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase text-[10px] font-black tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4">{{__("Estado")}}</th>
                        <th scope="col" class="px-6 py-4">{{__("Remitente")}}</th>
                        <th scope="col" class="px-6 py-4 w-1/2">{{__("Mensaje")}}</th>
                        <th scope="col" class="px-6 py-4">{{__("Fecha")}}</th>
                        <th scope="col" class="px-6 py-4 text-right">{{__("Acciones")}}</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-gray-50 transition-colors {{ !$msg->is_read ? 'bg-yellow-50/30' : '' }}">

                            {{-- Estado simplificado en la tabla --}}
                            <td class="px-6 py-4">
                                @if(!$msg->is_read)
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-bold bg-[#D4AF37] text-black">
            <span class="w-1.5 h-1.5 rounded-full bg-black animate-pulse"></span>
            {{__("Nuevo")}}
        </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded-md text-xs font-medium bg-gray-100 text-gray-500">
            {{__("Leído")}}
        </span>
                                @endif
                            </td>

                            {{-- Remitente --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-black text-[#D4AF37] flex items-center justify-center font-serif font-bold text-xs flex-shrink-0">
                                        {{ substr($msg->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $msg->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $msg->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Mensaje --}}
                            <td class="px-6 py-4 whitespace-normal min-w-[300px]">
                                <p class="text-sm {{ !$msg->is_read ? 'text-black font-medium' : 'text-gray-600' }} line-clamp-2">
                                    {{ $msg->message }}
                                </p>
                            </td>

                            {{-- Fecha --}}
                            <td class="px-6 py-4 text-xs font-medium text-gray-500">
                                {{ $msg->created_at->format('d M Y') }}<br>
                                <span class="text-gray-400">{{ $msg->created_at->format('H:i') }}</span>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-6 py-4 text-right space-x-2">

                                {{-- Solo mostramos el botón de responder si NO es una respuesta tuya --}}
                                @if(!$msg->is_admin_reply)
                                    <button type="button" onclick="openReplyModal('{{ route('admin.support.reply', $msg->id) }}', '{{ $msg->email }}', '{{ addslashes($msg->name) }}')"
                                            class="text-xs font-bold text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                        {{__("Responder")}}
                                    </button>
                                @endif

                                @if(!$msg->is_read && !$msg->is_admin_reply)
                                    <form action="{{ route('admin.support.read', $msg->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs font-bold text-[#D4AF37] hover:text-black bg-black px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                            {{__("Marcar Leído")}}
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.support.destroy', $msg->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este mensaje?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-500 hover:text-white border border-red-200 hover:bg-red-500 px-3 py-1.5 rounded-lg transition-colors">
                                        {{__("Borrar")}}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">{{__("No hay mensajes en la bandeja de entrada.")}}</p>
                                <p class="text-sm text-gray-400 mt-1">{{__("Cuando los usuarios te escriban, aparecerán aquí.")}}</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if(isset($messages) && $messages->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL PARA RESPONDER --}}
    <div id="replyModal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-2xl transform transition-transform scale-100 mx-4">
            <h3 class="text-xl font-bold font-serif mb-1">{{__("Responder a")}} <span id="modal-name" class="text-[#D4AF37]"></span></h3>
            <p class="text-xs text-gray-500 mb-6" id="modal-email"></p>

            <form id="replyForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">{{__("Tu Mensaje")}}</label>
                    <textarea name="reply_message" rows="5"
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-[#D4AF37] focus:border-[#D4AF37] resize-none shadow-inner"
                              required placeholder="{{__('Escribe tu respuesta aquí...')}}"></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeReplyModal()" class="px-5 py-2.5 text-xs font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        {{__("Cancelar")}}
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-bold uppercase tracking-widest bg-black text-[#D4AF37] hover:bg-gray-900 rounded-xl shadow-lg transition-transform active:scale-95 flex items-center gap-2">
                        {{-- Flecha recta hacia la derecha --}}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                        {{__("Enviar Respuesta")}}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Ahora recibimos actionUrl en lugar de id
        function openReplyModal(actionUrl, email, name) {
            document.getElementById('replyModal').classList.remove('hidden');
            document.getElementById('modal-name').innerText = name;
            document.getElementById('modal-email').innerText = email;

            // Asignamos la URL exacta que nos ha dado Laravel
            document.getElementById('replyForm').action = actionUrl;
        }

        function closeReplyModal() {
            document.getElementById('replyModal').classList.add('hidden');
            document.querySelector('textarea[name="reply_message"]').value = '';
        }
    </script>
</x-layouts.admin>
