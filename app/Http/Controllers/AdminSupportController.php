<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportMessage;

class AdminSupportController extends Controller
{
    public function index()
    {
        // Filtramos para que SOLO aparezcan mensajes de usuarios (no respuestas del admin)
        $messages = \App\Models\SupportMessage::where('is_admin_reply', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // El contador de "nuevos" ya lo hace correctamente sobre mensajes no leídos
        $unreadCount = \App\Models\SupportMessage::where('is_read', false)
            ->where('is_admin_reply', false)
            ->count();

        return view('admin.support.index', compact('messages', 'unreadCount'));
    }

    public function markAsRead(SupportMessage $message)
    {
        $message->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Mensaje marcado como leído.');
    }

    public function destroy(SupportMessage $message)
    {
        $message->delete();
        return redirect()->back()->with('success', 'Mensaje eliminado correctamente.');
    }

    public function reply(Request $request, SupportMessage $message)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        // 1. Creamos la respuesta en la base de datos como un mensaje del Admin
        SupportMessage::create([
            'user_id' => $message->user_id, // Lo vinculamos al mismo usuario
            'name' => 'Soporte Lectio',
            'email' => 'soporte@lectio.com',
            'message' => $request->reply_message,
            'is_read' => true, // Las respuestas del admin ya están leídas
            'is_admin_reply' => true, // <-- El interruptor mágico
        ]);

        // 2. Marcamos el mensaje original del cliente como leído
        $message->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Respuesta enviada al chat del usuario.');
    }
}
