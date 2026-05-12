<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportMessage;

class AdminSupportController extends Controller
{
    public function index()
    {
        $messages = \App\Models\SupportMessage::where('is_admin_reply', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

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

        SupportMessage::create([
            'user_id' => $message->user_id,
            'name' => 'Soporte Lectio',
            'email' => 'soporte@lectio.com',
            'message' => $request->reply_message,
            'is_read' => true,
            'is_admin_reply' => true,
        ]);

        $message->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Respuesta enviada al chat del usuario.');
    }
}
