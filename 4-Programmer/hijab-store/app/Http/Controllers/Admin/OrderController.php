<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{

    /**
     * Menampilkan daftar seluruh pesanan dengan filter status dan pencarian.
     */
    public function index(Request $request)
    {
        $query = Order::with('orderItems.productVariant.product')->latest();

        // 1. Filter Berdasarkan Tab Status Pesanan / Pembayaran
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            if ($status == 'unpaid') {
                $query->where('payment_status', 'unpaid');
            } elseif ($status == 'dikemas') {
                $query->where('order_status', 'dikemas');
            } elseif (in_array($status, ['diantar', 'dikirim'])) {
                $query->whereIn('order_status', ['diantar', 'dikirim']);
            } elseif ($status == 'selesai') {
                $query->where('order_status', 'selesai');
            }
        }

        // 2. Pencarian Berdasarkan ID Invoice, Nama Pelanggan, No HP, atau Alamat
        if ($request->has('search') && $request->search != '') {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('shipping_address', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->get();

        // Menghitung statistik untuk badge jumlah pada tiap tab filter
        $counts = [
            'all'      => Order::count(),
            'unpaid'   => Order::where('payment_status', 'unpaid')->count(),
            'dikemas'  => Order::where('order_status', 'dikemas')->count(),
            'diantar'  => Order::whereIn('order_status', ['diantar', 'dikirim'])->count(),
            'selesai'  => Order::where('order_status', 'selesai')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    /**
     * Menampilkan detail pesanan tertentu.
     */
    public function show($id)
    {
        $order = Order::with('orderItems.productVariant.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Memperbarui status pesanan dan nomor resi pengiriman.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:pending,dikemas,diantar,dikirim,selesai,dibatalkan,batal',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        
        $order->update([
            'order_status' => $request->order_status,
            'tracking_number' => $request->tracking_number,
        ]);

        return redirect()->route('admin.orders.show', $id)->with('success', 'Status pesanan dan nomor resi berhasil diperbarui!');
    }
}