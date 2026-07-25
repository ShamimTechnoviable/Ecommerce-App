<?php

use App\Http\Controllers\CartController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// ১. হোমপেজ, সার্চ ও ক্যাটাগরি ফিল্টার
Route::get('/', function (Request $request) {
    $query = Product::with('category');

    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->has('category') && $request->category != '') {
        $query->where('category_id', $request->category);
    }

    $products = $query->latest()->get();
    $categories = Category::all();

    return view('welcome', compact('products', 'categories'));
});

// ২. কার্ট ও চেকাউট রাউটস
Route::post('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::delete('/remove-from-cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::patch('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');

// অর্ডার ট্র্যাকিং পেজ
use App\Http\Controllers\OrderTrackingController;
Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('order.track');
Route::post('/track-order', [OrderTrackingController::class, 'search'])->name('order.track.search');
// ৩. সিঙ্গেল প্রোডাক্ট ডিটেইলস রাউট
Route::get('/product/{id}', function ($id) {
    $product = Product::with('category')->findOrFail($id);

    $relatedProducts = Product::where('category_id', $product->category_id)
                              ->where('id', '!=', $product->id)
                              ->take(4)
                              ->get();

    return view('product-details', compact('product', 'relatedProducts'));
})->name('product.details');