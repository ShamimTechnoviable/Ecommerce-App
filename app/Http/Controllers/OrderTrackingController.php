<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('track-order');
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required',
        ], [
            'search.required' => 'অনুগ্রহ করে মোবাইল নম্বর বা অর্ডার আইডি দিন।'
        ]);

        $search = $request->input('search');

        $orders = Order::with('items.product')
            ->where('phone', $search)
            ->orWhere('id', $search)
            ->latest()
            ->get();

        return view('track-order', compact('orders', 'search'));
    }
}