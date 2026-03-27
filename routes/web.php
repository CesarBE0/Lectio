<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;

// RUTAS DE USUARIO AUTENTICADO
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/cupon', [CheckoutController::class, 'applyCoupon'])->name('coupon.apply');
    Route::post('/checkout/cupon/quitar', [CheckoutController::class, 'removeCoupon'])->name('coupon.remove');

    Route::get('/mi-cuenta', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mi-cuenta', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/mis-pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/mis-pedidos/factura/{id}', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');

    Route::get('/biblioteca', [LibraryController::class, 'index'])->name('library.index');
    Route::post('/biblioteca/favorito/{id}', [LibraryController::class, 'toggleFavorite'])->name('library.favorite');
});

// IDIOMAS
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es', 'fr', 'it', 'de', 'pt', 'ja', 'zh'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// RUTAS DE ADMINISTRADOR
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/inventario', [AdminController::class, 'inventory'])->name('admin.inventory');
    Route::get('/pedidos', [AdminController::class, 'orders'])->name('admin.orders');

    // Panel Admin - Bandeja de Soporte
    Route::get('/soporte', [App\Http\Controllers\AdminSupportController::class, 'index'])->name('admin.support.index');
    Route::post('/soporte/{message}/reply', [App\Http\Controllers\AdminSupportController::class, 'reply'])->name('admin.support.reply');
    Route::patch('/soporte/{message}/read', [App\Http\Controllers\AdminSupportController::class, 'markAsRead'])->name('admin.support.read');
    Route::delete('/soporte/{message}', [App\Http\Controllers\AdminSupportController::class, 'destroy'])->name('admin.support.destroy');

    // CRUD LIBROS
    Route::get('/libro/crear', [AdminController::class, 'createBook'])->name('admin.books.create');
    Route::post('/libro/guardar', [AdminController::class, 'storeBook'])->name('admin.books.store');
    Route::get('/libro/{id}/editar', [AdminController::class, 'editBook'])->name('admin.books.edit');
    Route::put('/libro/{id}/actualizar', [AdminController::class, 'updateBook'])->name('admin.books.update');
    Route::delete('/libro/{id}/eliminar', [AdminController::class, 'destroyBook'])->name('admin.books.destroy');

    // DETALLES Y ELIMINACIÓN DE PEDIDOS
    Route::get('/pedidos/{orderNumber}/detalle', [AdminController::class, 'getOrderDetails'])->name('admin.orders.details');
    Route::delete('/pedidos/{orderNumber}', [AdminController::class, 'destroyOrder'])->name('admin.orders.destroy');

    // CUPONES
    Route::get('/coupons', [CouponController::class, 'index'])->name('admin.coupons.index');
    Route::post('/coupons', [CouponController::class, 'store'])->name('admin.coupons.store');
    Route::put('/coupons/{coupon}', [CouponController::class, 'toggle'])->name('admin.coupons.toggle');
});

// PÚBLICO
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalogo');
Route::get('/libro/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrito/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrito/actualizar', [CartController::class, 'updateQuantity'])->name('cart.update');

Route::get('/vaciar-carrito', function () {
    session()->forget('cart');
    return redirect()->route('catalogo');
});

Route::post('/books/{book}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

Route::get('/contacto', [App\Http\Controllers\ContactController::class, 'index'])->name('contacto');
Route::post('/contacto', [App\Http\Controllers\ContactController::class, 'store'])->name('contacto.store');

require __DIR__ . '/auth.php';
