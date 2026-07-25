<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // কার্টে প্রোডাক্ট যোগ করা
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        // ১. স্টক চেক (স্টক ০ বা তার কম হলে কার্টে যোগ করতে দেবে না)
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'দুঃখিত, এই প্রোডাক্টটি বর্তমানে স্টক আউট!');
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'প্রোডাক্ট কার্টে যোগ হয়েছে!');
    }

    // কার্ট পেজ দেখানো
    public function showCart()
    {
        return view('cart');
    }

    // কার্ট থেকে প্রোডাক্ট ডিলিট করা
    public function removeFromCart($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'প্রোডাক্ট কার্ট থেকে সরানো হয়েছে!');
    }

    // অর্ডার প্রসেস করা (Cash on Delivery) + অটোমেটিক স্টক কমানো
    public function checkout(Request $request)
    {
        $cart = session()->get('cart');

        if (!$cart || count($cart) == 0) {
            return redirect()->back()->with('error', 'আপনার কার্ট খালি!');
        }

        // ইনপুট ভ্যালিডেশন
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        // সর্বমোট মূল্য হিসাব
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // ১. অর্ডার তৈরি করা
        $order = Order::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // ২. অর্ডারের আইটেমগুলো সেভ করা এবং স্টক কমানো
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // ২. অর্ডারের সাথে অটোমেটিক স্টক কমানো
            $product = Product::find($productId);
            if ($product) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        // ৩. অর্ডার শেষে কার্ট খালি করা
        session()->forget('cart');

        return redirect()->route('cart.show')->with('success', 'আপনার অর্ডারটি সফলভাবে জমা হয়েছে! ধন্যবাদ।');
    }
}