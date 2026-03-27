<x-layouts.layout title="Chat de Soporte - Lectio">
    <div class="container mx-auto px-4 py-8 max-w-3xl">

        {{-- CONTENEDOR DEL CHAT --}}
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col h-[700px]">

            {{-- CABECERA DEL CHAT --}}
            <div class="bg-black p-4 flex items-center gap-4 shadow-md z-10 relative">
                <div class="w-12 h-12 rounded-full bg-[#D4AF37] flex items-center justify-center text-black font-serif font-bold text-xl border-2 border-white">
                    L
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg leading-tight">{{__("Soporte Lectio")}}</h2>
                    <p class="text-[#D4AF37] text-xs flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        {{__("En línea (Respondemos en 24h)")}}
                    </p>
                </div>
            </div>

            {{-- ZONA DE MENSAJES --}}
            <div class="flex-1 bg-gray-50 p-6 overflow-y-auto flex flex-col gap-4" id="chat-box">

                {{-- Mensaje Automático de Lectio --}}
                <div class="flex items-end gap-2 w-full max-w-[85%]">
                    <div class="w-8 h-8 rounded-full bg-black flex-shrink-0 flex items-center justify-center text-[#D4AF37] text-xs font-serif font-bold">L</div>
                    <div class="bg-white border border-gray-200 p-4 rounded-2xl rounded-bl-none shadow-sm">
                        <p class="text-sm text-gray-700">¡Hola, {{ Auth::check() ? Auth::user()->name : 'lector' }}! 👋</p>
                        <p class="text-sm text-gray-700 mt-1">Soy el asistente de Lectio. Escribe aquí tu consulta sobre pedidos, formatos o tu cuenta, y nuestro equipo te responderá lo antes posible.</p>
                    </div>
                </div>

                {{-- Historial de mensajes del usuario y del Admin --}}
                @foreach($messages as $msg)
                    @if($msg->is_admin_reply)
                        {{-- Mensaje del ADMIN (Izquierda, blanco) --}}
                        <div class="flex items-end gap-2 w-full max-w-[85%] mt-4 animate-fade-in-up">
                            <div class="w-8 h-8 rounded-full bg-black flex-shrink-0 flex items-center justify-center text-[#D4AF37] text-xs font-serif font-bold shadow-md">L</div>
                            <div class="bg-white border border-gray-200 p-4 rounded-2xl rounded-bl-none shadow-sm relative">
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $msg->message }}</p>
                                <span class="text-[9px] font-bold text-gray-400 mt-2 block">{{ $msg->created_at->format('H:i - d/m/Y') }}</span>
                            </div>
                        </div>
                    @else
                        {{-- Mensaje del USUARIO (Derecha, Dorado) --}}
                        <div class="flex flex-col items-end w-full mt-4 animate-fade-in-up">
                            <div class="bg-[#D4AF37] text-black p-3.5 rounded-2xl rounded-br-none shadow-md max-w-[85%] relative">
                                <p class="text-sm font-medium leading-relaxed">{{ $msg->message }}</p>
                            </div>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-[9px] font-bold text-gray-400">{{ $msg->created_at->format('H:i - d/m/Y') }}</span>
                                @if($msg->is_read)
                                    <svg class="w-3 h-3 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Mensaje de éxito temporal --}}
                @if(session('success'))
                    <div class="flex items-end gap-2 w-full max-w-[85%] mt-2 animate-fade-in-up">
                        <div class="w-8 h-8 rounded-full bg-black flex-shrink-0 flex items-center justify-center text-[#D4AF37] text-xs font-serif font-bold">L</div>
                        <div class="bg-gray-800 p-3 rounded-2xl rounded-bl-none shadow-sm">
                            <p class="text-sm text-[#D4AF37] font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{__("Hemos recibido tu mensaje. Lo revisaremos enseguida.")}}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ZONA DE INPUT (Formulario) --}}
            <div class="bg-white p-4 border-t border-gray-100">
                <form action="{{ route('contacto.store') }}" method="POST" class="flex items-end gap-2 relative">
                    @csrf
                    <textarea name="message" rows="2"
                              class="w-full bg-gray-100 border-none rounded-2xl px-4 py-3 text-sm text-gray-700 focus:ring-2 focus:ring-[#D4AF37] resize-none"
                              placeholder="{{__('Escribe tu mensaje aquí...')}}" required></textarea>

                    <button type="submit" class="bg-black text-[#D4AF37] w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 hover:bg-gray-900 transition-transform active:scale-95 shadow-md">
                        {{-- Flecha recta hacia la derecha --}}
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </form>
                @if(!Auth::check())
                    <p class="text-[10px] text-center text-gray-400 mt-2">
                        {{__("Estás enviando este mensaje como invitado. Si deseas seguimiento,")}} <a href="{{ route('login') }}" class="text-[#D4AF37] font-bold">{{__("inicia sesión")}}</a>.
                    </p>
                @endif
            </div>
        </div>

    </div>

    {{-- Script para que el chat siempre baje hasta el último mensaje automáticamente --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var chatBox = document.getElementById("chat-box");
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    </script>

    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-layouts.layout>
