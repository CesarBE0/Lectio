<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportMessage;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $userId = \Illuminate\Support\Facades\Auth::id();

            \App\Models\SupportMessage::where('user_id', $userId)
                ->where('is_admin_reply', true)
                ->where('user_read', false)
                ->update(['user_read' => true]);

            $messages = \App\Models\SupportMessage::where('user_id', $userId)->get();
        } else {
            $messages = collect();
        }

        return view('contacto', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        SupportMessage::create([
            'user_id' => Auth::id(),
            'name' => Auth::check() ? Auth::user()->name : 'Invitado',
            'email' => Auth::check() ? Auth::user()->email : 'anonimo@lectio.com',
            'message' => $request->message
        ]);

        return redirect()->back()->with('success', 'Mensaje enviado');
    }
}
