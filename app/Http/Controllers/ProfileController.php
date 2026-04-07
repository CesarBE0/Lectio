<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Coupon;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
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

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->preferred_language = $request->preferred_language;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Tus datos se han actualizado con éxito ✨');
    }

    // NUEVO: Función para canjear Puntos Lectio
    public function redeemPoints()
    {
        $user = Auth::user();

        if ($user->points >= 100) {
            $user->decrement('points', 100);

            $code = 'PUNTOS-' . strtoupper(Str::random(6));

            Coupon::create([
                'user_id' => $user->id,
                'code' => $code,
                'discount_percentage' => 5, // 5% de descuento por 100 puntos
                'is_active' => true
            ]);

            return back()->with('success', "¡Enhorabuena! Has canjeado 100 puntos. Tu código de descuento del 5% es: {$code}");
        }

        return back()->with('error', 'Necesitas al menos 100 Puntos Lectio para canjear un cupón.');
    }
}
