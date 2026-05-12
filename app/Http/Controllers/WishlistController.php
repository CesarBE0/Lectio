<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::with('book')->where('user_id', Auth::id())->get();
        return view('wishlist.index', compact('wishlist'));
    }

    public function toggle(Request $request, $bookId)
    {
        $userId = Auth::id();
        $wishlist = Wishlist::where('user_id', $userId)->where('book_id', $bookId)->first();

        if($wishlist) {
            $wishlist->delete();
            $isWished = false;
            $message = 'Libro eliminado de tu lista de deseos.';
        } else {
            Wishlist::create(['user_id' => $userId, 'book_id' => $bookId]);
            $isWished = true;
            $message = '¡Libro guardado en tu lista de deseos!';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_wished' => $isWished,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}
