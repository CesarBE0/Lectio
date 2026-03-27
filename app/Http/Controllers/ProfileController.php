<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Mostrar el formulario
    public function edit()
    {
        $user = Auth::user();

        $languages = [
            ['code' => 'es', 'name' => 'Español',       'flag_country' => 'es'],
            ['code' => 'en', 'name' => 'English',       'flag_country' => 'gb'],
            ['code' => 'fr', 'name' => 'Français',      'flag_country' => 'fr'],
            ['code' => 'it', 'name' => 'Italiano',      'flag_country' => 'it'],
            ['code' => 'de', 'name' => 'Deutsch',       'flag_country' => 'de'],
            ['code' => 'pt', 'name' => 'Português',     'flag_country' => 'pt'],
            ['code' => 'ja', 'name' => '日本語 (Japonés)', 'flag_country' => 'jp'],
            ['code' => 'zh', 'name' => '中文 (Chino)',    'flag_country' => 'cn'],
        ];

        return view('profile.edit', compact('user', 'languages'));
    }

    // Guardar los datos
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validamos los datos que nos envía el usuario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            // Si escribe una contraseña, debe coincidir con la confirmación
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // 2. Actualizamos los datos básicos
        $user->name = $request->name;
        $user->email = $request->email;
        $user->preferred_language = $request->preferred_language;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;

        // 3. Si ha rellenado la contraseña, la encriptamos y la guardamos
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // 4. Volvemos atrás con un mensaje (Nuestra alerta SweetAlert2 lo atrapará)
        return redirect()->back()->with('success', 'Tus datos se han actualizado con éxito ✨');
    }
}
