<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Lista de idiomas con su código de PAÍS para la bandera (flag_country)
        $languages = [
            ['code' => 'es', 'name' => 'Español',       'flag_country' => 'es'],
            ['code' => 'en', 'name' => 'English',       'flag_country' => 'gb'], // Bandera UK
            ['code' => 'fr', 'name' => 'Français',      'flag_country' => 'fr'],
            ['code' => 'it', 'name' => 'Italiano',      'flag_country' => 'it'],
            ['code' => 'de', 'name' => 'Deutsch',       'flag_country' => 'de'],
            ['code' => 'pt', 'name' => 'Português',     'flag_country' => 'pt'],
            ['code' => 'ja', 'name' => '日本語 (Japonés)', 'flag_country' => 'jp'],
            ['code' => 'zh', 'name' => '中文 (Chino)',    'flag_country' => 'cn'],
        ];

        return view('settings.index', compact('user', 'languages'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validamos los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        // Guardamos
        $user->update($validated);

        return redirect()->back()->with('success', __('¡Datos actualizados correctamente!'));
    }
}
