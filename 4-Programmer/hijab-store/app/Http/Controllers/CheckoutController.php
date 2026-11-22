<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\ProductVariant;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    // Menampilkan Halaman Checkout dengan Data Provinsi RajaOngkir
    public function index(Request $request)
    {
        $selectedVariantIds = $request->input('selected_items', []);

        if (empty($selectedVariantIds)) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk untuk di-checkout.');
        }

        $user = Auth::user();
        $cartItems = [];
        $totalWeight = 0;
        $subtotal = 0;

        // Ambil data keranjang sesuai varian yang dicentang user
        if ($user) {
            $cartItems = Cart::with(['variant.product'])
                ->where('user_id', $user->id)
                ->whereIn('product_variant_id', $selectedVariantIds)
                ->get();

            foreach ($cartItems as $item) {
                $price = $item->variant->product->price ?? 0;
                $weight = $item->variant->product->weight ?? 1000;
                $subtotal += $price * $item->quantity;
                $totalWeight += $weight * $item->quantity;
            }
        } else {
            $cartSession = session()->get('cart', []);
            $cartItems = array_intersect_key($cartSession, array_flip($selectedVariantIds));

            foreach ($cartItems as $variantId => $item) {
                $variant = ProductVariant::with('product')->find($variantId);
                $price = $item['price'] ?? 0;
                $weight = $variant->product->weight ?? 1000;
                $subtotal += $price * $item['quantity'];
                $totalWeight += $weight * $item['quantity'];
            }
        }

        // Ambil daftar Provinsi dari RajaOngkir Service
        $provinces = $this->rajaOngkir->getProvinces();

        return view('checkout', compact(
            'cartItems', 
            'selectedVariantIds', 
            'user', 
            'provinces', 
            'subtotal', 
            'totalWeight'
        ));
    }

    // Memproses Pesanan, Ongkir RajaOngkir, & Generate Midtrans Snap Token
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'city_id'          => 'required',
            'courier'           => 'required|string',
            'shipping_cost'    => 'required|numeric|min:0',
        ]);

        $selectedVariantIds = $request->input('selected_items', []);
        $itemsToProcess = [];

        // Ambil item dari DB (User Login) atau Session (Guest)
        if (Auth::check()) {
            $user = Auth::user();

            // AUTO-UPDATE PROFIL PELANGGAN
            if (empty($user->phone) || empty($user->address)) {
                $user->update([
                    'phone'   => $user->phone ?? $request->customer_phone,
                    'address' => $user->address ?? $request->shipping_address,
                ]);
            }

            $cartQuery = Cart::with(['variant.product'])->where('user_id', $user->id);
            if (!empty($selectedVariantIds)) {
                $cartQuery->whereIn('product_variant_id', $selectedVariantIds);
            }
            
            $dbCarts = $cartQuery->get();
            foreach ($dbCarts as $dbCart) {
                $variant = $dbCart->variant;

                // VALIDASI STOK: Cek apakah stok varian mencukupi
                if ($variant && $dbCart->quantity > $variant->stock) {
                    $productName = $variant->product->name ?? 'Produk';
                    return back()->with('error', "Stok untuk '{$productName}' (Varian: {$variant->color}) tidak mencukupi. Sisa stok: {$variant->stock}");
                }

                $itemsToProcess[] = [
                    'variant_id' => $dbCart->product_variant_id,
                    'name'       => $dbCart->variant->product->name ?? 'Produk Shea Daily',
                    'price'      => $dbCart->variant->product->price,
                    'quantity'   => $dbCart->quantity,
                    'weight'     => $dbCart->variant->product->weight ?? 1000,
                ];
            }
        } else {
            $cartSession = session()->get('cart', []);
            if (!empty($selectedVariantIds)) {
                $cartSession = array_intersect_key($cartSession, array_flip($selectedVariantIds));
            }

            foreach ($cartSession as $variantId => $item) {
                $variant = ProductVariant::with('product')->find($variantId);

                // VALIDASI STOK UNTUK GUEST USER
                if ($variant && $item['quantity'] > $variant->stock) {
                    $productName = $variant->product->name ?? 'Produk';
                    return back()->with('error', "Stok untuk '{$productName}' (Varian: {$variant->color}) tidak mencukupi. Sisa stok: {$variant->stock}");
                }

                $itemsToProcess[] = [
                    'variant_id' => $variantId,
                    'name'       => $item['name'] ?? 'Produk Shea Daily',
                    'price'      => $item['price'],
                    'quantity'   => $item['quantity'],
                    'weight'     => $item['weight'] ?? 1000,
                ];
            }
        }

        if (empty($itemsToProcess)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Hitung total belanja produk
        $totalBelanja = 0;
        foreach ($itemsToProcess as $item) {
            $totalBelanja += $item['price'] * $item['quantity'];
        }

        // Ambil Ongkos Kirim Dinamis hasil perhitungan RajaOngkir
        $shippingCost = (int) $request->shipping_cost;
        $totalAmount  = $totalBelanja + $shippingCost;
        $invoiceId    = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // 1. PENGECEKAN KEAMANAN KONFIGURASI MIDTRANS
        $serverKey = config('services.midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        if (empty($serverKey)) {
            return back()->with('error', 'Sistem Kesalahan: Konfigurasi Server Key Midtrans tidak ditemukan.');
        }

        Config::$serverKey    = $serverKey;
        Config::$isProduction = config('services.midtrans.is_production') == true; 
        Config::$isSanitized  = config('services.midtrans.is_sanitized') == true;
        Config::$is3ds        = config('services.midtrans.is_3ds') == true;

        // Menyusun daftar item untuk Midtrans
        $midtransItems = [];
        foreach ($itemsToProcess as $item) {
            $midtransItems[] = [
                'id'       => $item['variant_id'],
                'price'    => (int) $item['price'],
                'quantity' => (int) $item['quantity'],
                'name'     => substr($item['name'], 0, 50),
            ];
        }

        // Item Ongkos Kirim Dinamis RajaOngkir
        if ($shippingCost > 0) {
            $midtransItems[] = [
                'id'       => 'SHIPPING',
                'price'    => $shippingCost,
                'quantity' => 1,
                'name'     => 'Ongkir ' . strtoupper($request->courier),
            ];
        }

        // Payload Midtrans Snap
        $params = [
            'transaction_details' => [
                'order_id'     => $invoiceId,
                'gross_amount' => (int) $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => Auth::user()->email ?? 'guest@sheadaily.com',
                'phone'      => $request->customer_phone,
            ],
            'item_details' => $midtransItems,
        ];

        // 2. TRANSAKSI DATABASE
        DB::beginTransaction();
        try {
            // Generate Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Simpan ke tabel Orders (Origin City Jakarta Utara: 151)
            $order = Order::create([
                'id'               => $invoiceId,
                'user_id'          => Auth::id() ?? null,
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'city_id'          => $request->city_id,
                'courier'          => $request->courier,
                'shipping_cost'    => $shippingCost,
                'total_amount'     => $totalAmount,
                'payment_method'   => 'midtrans',
                'payment_status'   => 'unpaid',
                'order_status'     => 'pending',
                'snap_token'       => $snapToken,
            ]);

            // Simpan item pesanan ke OrderItems
            foreach ($itemsToProcess as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $item['variant_id'],
                    'quantity'           => $item['quantity'],
                    'price'              => $item['price'],
                ]);
            }

            // Bersihkan Keranjang setelah checkout berhasil
            if (Auth::check()) {
                $processedVariantIds = array_column($itemsToProcess, 'variant_id');
                Cart::where('user_id', Auth::id())
                    ->whereIn('product_variant_id', $processedVariantIds)
                    ->delete();
            } else {
                session()->forget('cart');
            }

            DB::commit();

            return redirect()->route('order.success', $order->id)->with('success', 'Pesanan berhasil dibuat!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Checkout Store Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran dengan Midtrans. Pesan: ' . $e->getMessage());
        }
    }

    public function success($id)
    {
        $order = Order::with('orderItems.productVariant.product')->findOrFail($id);
        return view('order-success', compact('order'));
    }

    // Webhook/Callback dari Midtrans untuk Update Status Pesanan & Potong Stok Otomatis
    public function callback(Request $request)
    {
        Config::$serverKey    = config('services.midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = config('services.midtrans.is_production') == true;

        try {
            $notification = new Notification();
            
            $transactionStatus = $notification->transaction_status;
            $orderId           = $notification->order_id;
            $fraudStatus       = $notification->fraud_status;

            $order = Order::with('orderItems.productVariant')->find($orderId);

            if (!$order) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Order ID ' . $orderId . ' tidak ditemukan di database lokal.'
                ], 404);
            }

            $isPaid = ($transactionStatus == 'settlement') || ($transactionStatus == 'capture' && $fraudStatus == 'accept');

            if ($isPaid) {
                if ($order->payment_status != 'paid') {
                    DB::transaction(function () use ($order) {
                        $order->update([
                            'payment_status' => 'paid',
                            'order_status'   => 'dikemas',
                        ]);

                        foreach ($order->orderItems as $item) {
                            if ($item->productVariant) {
                                $item->productVariant->decrement('stock', $item->quantity);
                            }
                        }
                    });
                }
            } else if ($transactionStatus == 'pending') {
                $order->update([
                    'payment_status' => 'unpaid',
                    'order_status'   => 'pending',
                ]);
            } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                if ($order->payment_status == 'paid') {
                    foreach ($order->orderItems as $item) {
                        if ($item->productVariant) {
                            $item->productVariant->increment('stock', $item->quantity);
                        }
                    }
                }

                $order->update([
                    'payment_status' => 'failed',
                    'order_status'   => 'batal',
                ]);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Status pesanan dan stok produk berhasil diperbarui'
            ], 200);

        } catch (Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());

            if (str_contains($e->getMessage(), "Transaction doesn't exist")) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Transaksi tidak terdaftar di Midtrans Sandbox.'
                ], 404);
            }

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}