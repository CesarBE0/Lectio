<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return view('admin.coupons', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code|max:255',
            'discount_percentage' => 'required|integer|min:1|max:100',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'discount_percentage' => $request->discount_percentage,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', '¡Cupón creado con éxito para Lectio!');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update([
            'is_active' => !$coupon->is_active
        ]);

        return redirect()->back()->with('success', 'Estado del cupón actualizado.');
    }
}
