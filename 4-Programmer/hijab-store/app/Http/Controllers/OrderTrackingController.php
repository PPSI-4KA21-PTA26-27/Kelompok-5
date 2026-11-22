<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('order-tracking');
    }

    public function search(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ], [
            'order_id.required' => 'Masukkan nomor invoice Anda.',
        ]);

        $order = Order::with('orderItems.productVariant.product')
            ->where('id', trim($request->order_id))
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan dengan Nomor Invoice tersebut tidak ditemukan. Mohon periksa kembali.');
        }

        return view('order-tracking', compact('order'));
    }
}
