<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductVariant::with(['product.category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%")
                  ->orWhereHas('product', fn($pq) => $pq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filter === 'low') {
            $query->where('stock', '<=', 5);
        } elseif ($request->filter === 'empty') {
            $query->where('stock', 0);
        }

        $variants = $query->orderBy('stock', 'asc')->get();

        return view('admin.stocks.index', compact('variants'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $variant = ProductVariant::findOrFail($id);
        $variant->update(['stock' => $request->stock]);

        return back()->with('success', 'Stok varian "' . ($variant->product->name ?? '') . ' - ' . $variant->color . '" berhasil diperbarui menjadi ' . $request->stock . ' pcs.');
    }
}