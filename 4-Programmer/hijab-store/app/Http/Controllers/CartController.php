<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Ambil dari database jika user sudah login
            $cartItems = Cart::with(['variant.product'])->where('user_id', Auth::id())->get();
            
            $cart = [];
            foreach ($cartItems as $item) {
                $variant = $item->variant;
                $productImage = $variant->image ?: $variant->product->image;

                $cart[$variant->id] = [
                    "cart_id" => $item->id,
                    "variant_id" => $variant->id,
                    "name" => $variant->product->name,
                    "color" => $variant->color,
                    "price" => $variant->product->price,
                    "quantity" => $item->quantity,
                    "weight" => $variant->product->weight ?? 0,
                    "slug" => $variant->product->slug,
                    "image" => $productImage
                ];
            }
        } else {
            // Ambil dari Session jika guest/belum login
            $cart = session()->get('cart', []);
        }

        return view('cart', compact('cart'));
    }

    public function add(Request $request, $variantId)
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);
        $quantity = $request->input('quantity', 1);

        if (Auth::check()) {
            // Simpan ke Database
            $cartItem = Cart::where('user_id', Auth::id())
                            ->where('product_variant_id', $variantId)
                            ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_variant_id' => $variantId,
                    'quantity' => $quantity
                ]);
            }
        } else {
            // Simpan ke Session
            $cart = session()->get('cart', []);
            $productImage = $variant->image ?: $variant->product->image;

            if (isset($cart[$variantId])) {
                $cart[$variantId]['quantity'] += $quantity;
            } else {
                $cart[$variantId] = [
                    "variant_id" => $variant->id,
                    "name" => $variant->product->name,
                    "color" => $variant->color,
                    "price" => $variant->product->price,
                    "quantity" => $quantity,
                    "weight" => $variant->product->weight ?? 0,
                    "slug" => $variant->product->slug,
                    "image" => $productImage
                ];
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove(Request $request, $variantId)
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->where('product_variant_id', $variantId)
                ->delete();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$variantId])) {
                unset($cart[$variantId]);
                session()->put('cart', $cart);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}