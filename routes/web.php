<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\CheckoutController;

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/configuracion', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/configuracion', [SettingsController::class, 'update'])->name('settings.update');
});

Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es', 'fr', 'it', 'de', 'pt', 'ja', 'zh'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/inventario', [AdminController::class, 'inventory'])->name('admin.inventory');
    Route::get('/pedidos', [AdminController::class, 'orders'])->name('admin.orders');

    // RUTAS CRUD
    Route::get('/libro/crear', [AdminController::class, 'createBook'])->name('admin.books.create');
    Route::post('/libro/guardar', [AdminController::class, 'storeBook'])->name('admin.books.store');
    Route::get('/libro/{id}/editar', [AdminController::class, 'editBook'])->name('admin.books.edit');
    Route::put('/libro/{id}/actualizar', [AdminController::class, 'updateBook'])->name('admin.books.update');
    Route::delete('/libro/{id}/eliminar', [AdminController::class, 'destroyBook'])->name('admin.books.destroy');
    Route::get('/pedidos/{id}/detalle', [AdminController::class, 'getOrderDetails'])->name('admin.orders.details');
    Route::delete('/pedidos/{id}', [AdminController::class, 'destroyOrder'])->name('admin.orders.destroy');

});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalogo');
Route::get('/libro/{id}', [BookController::class, 'show'])->name('books.show');


// Ruta para ver el carrito
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');

// Ruta para añadir libros (funciona para el botón Añadir y Comprar)
Route::post('/carrito/add/{id}', [CartController::class, 'add'])->name('cart.add');

// Ruta para eliminar libros del carrito
Route::post('/carrito/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/vaciar-carrito', function() {
    session()->forget('cart');
    return "¡El carrito de Lectio se ha vaciado correctamente y los errores antiguos se han borrado! <br><br> <a href='/catalogo' style='color:#D4AF37; font-weight:bold; background:black; padding:10px; text-decoration:none;'>Volver al Catálogo</a>";
});

Route::post('/carrito/actualizar', [CartController::class, 'updateQuantity'])->name('cart.update');

Route::get('/biblioteca', [LibraryController::class, 'index'])->middleware('auth')->name('library.index');

Route::post('/biblioteca/favorito/{id}', [LibraryController::class, 'toggleFavorite'])->middleware('auth')->name('library.favorite');

require __DIR__.'/auth.php';
