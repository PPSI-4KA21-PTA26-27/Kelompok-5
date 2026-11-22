@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-6xl mx-auto space-y-6">

    <!-- Back Link & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-[#24201D] transition mb-3">
                <span>← Kembali ke Daftar Pesanan</span>
            </a>
            <div class="flex items-center gap-3">
                <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Pesanan #{{ $order->id }}</h1>
                @php
                    $orderStatus = strtolower($order->order_status ?? 'pending');
                    $statusStyle = match($orderStatus) {
                        'dikemas' => ['bg' => '#DBEAFE', 'color' => '#1D4ED8', 'label' => 'Dikemas'],
                        'diantar', 'dikirim' => ['bg' => '#E0E7FF', 'color' => '#4338CA', 'label' => 'Diantar'],
                        'selesai' => ['bg' => '#DCFCE7', 'color' => '#15803D', 'label' => 'Selesai'],
                        'dibatalkan', 'batal' => ['bg' => '#FEE2E2', 'color' => '#B91C1C', 'label' => 'Dibatalkan'],
                        default   => ['bg' => '#FEF3C7', 'color' => '#B45309', 'label' => 'Menunggu Bayar'],
                    };
                @endphp
                <span style="background-color: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['color'] }};"
                      class="px-3 py-1 rounded-full font-semibold text-xs">
                    {{ $statusStyle['label'] }}
                </span>
            </div>
            <p class="text-xs text-gray-400 mt-1 font-mono">
                Dibuat pada {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
            </p>
        </div>
    </div>

    <!-- Alert Success Message -->
    @if(session('success'))
        <div class="bg-[#D1FAE5] border border-[#A7F3D0] text-[#065F46] px-4 py-3 rounded-xl text-xs font-medium flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-[#065F46] font-bold text-sm">×</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom Kiri: Data Penerima & Rincian Produk (2 Kolom) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card 1: Informasi Penerima & Pengiriman -->
            <div class="bg-white p-6 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] space-y-4">
                <div class="border-b border-[#F3EFE6] pb-3 flex justify-between items-center">
                    <h2 class="font-serif text-lg font-bold text-[#24201D]">Data Penerima & Pengiriman</h2>
                    <span class="text-[11px] font-mono text-gray-500">
                        Pembayaran:
                        <strong class="{{ $order->payment_status == 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ strtoupper($order->payment_status) }}
                        </strong>
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">Nama Penerima</p>
                        <p class="font-semibold text-sm text-[#24201D] mt-0.5">{{ $order->customer_name }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">No. WhatsApp / Telepon</p>
                        <p class="font-mono text-sm text-[#24201D] mt-0.5">{{ $order->customer_phone ?? '-' }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">Alamat Pengiriman</p>
                        <p class="text-xs text-[#24201D] mt-1 leading-relaxed bg-[#FAF7F2]/70 p-3.5 rounded-xl border border-[#D6C7B2]/50">
                            {{ $order->shipping_address ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">Kurir / Layanan</p>
                        <p class="font-semibold text-xs text-[#24201D] mt-1 uppercase">{{ $order->courier ?? 'JNE' }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">Nomor Resi Pengiriman</p>
                        <p class="font-mono text-xs font-semibold text-[#3A4A3B] mt-1">
                            {{ $order->tracking_number ?? 'Belum diinput' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Rincian Produk Dipesan -->
            <div class="bg-white p-6 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] space-y-4">
                <div class="border-b border-[#F3EFE6] pb-3">
                    <h2 class="font-serif text-lg font-bold text-[#24201D]">Rincian Item Pesanan</h2>
                </div>

                <div class="divide-y divide-[#F3EFE6]">
                    @foreach($order->items as $item)
                        @php
                            $variant = $item->productVariant ?? $item->variant;
                            $product = $variant->product ?? null;
                        @endphp
                        <div class="py-3.5 first:pt-0 last:pb-0 flex justify-between items-center text-xs">
                            <div class="flex items-center gap-3.5">
                                @if(optional($product)->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 object-cover rounded-xl border border-[#D6C7B2]/50 shrink-0">
                                @elseif(optional($variant)->image)
                                    <img src="{{ asset('storage/' . $variant->image) }}" class="w-12 h-12 object-cover rounded-xl border border-[#D6C7B2]/50 shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-[#FAF7F2] border border-[#D6C7B2]/50 flex items-center justify-center text-[10px] text-gray-400 shrink-0">
                                        Hijab
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-sm text-[#24201D]">{{ optional($product)->name ?? 'Produk Hijab' }}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">
                                        Varian: <span class="font-medium text-[#24201D]">{{ optional($variant)->color ?? '-' }}</span>
                                        @if(optional($variant)->size) • Ukuran: {{ $variant->size }} @endif
                                        • Qty: <span class="font-semibold text-[#24201D]">{{ $item->quantity }}</span>
                                    </p>
                                </div>
                            </div>
                            <span class="font-semibold text-sm text-[#24201D]">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-[#F3EFE6] space-y-2 text-xs">
                    @if(isset($order->shipping_cost))
                        <div class="flex justify-between text-gray-500">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center text-sm pt-1">
                        <span class="font-semibold text-[#24201D]">Total Pembayaran</span>
                        <strong class="font-serif text-xl text-[#3A4A3B]">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Panel Pembaruan Status & Resi (1 Kolom) -->
        <div class="bg-white p-6 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] h-fit space-y-5">
            <div class="border-b border-[#F3EFE6] pb-3">
                <h2 class="font-serif text-lg font-bold text-[#24201D]">Status & Nomor Resi</h2>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui status pemenuhan pesanan dan nomor resi kurir.</p>
            </div>

            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">Status Pesanan</label>
                    <select name="order_status" class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3.5 py-2.5 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
                        <option value="pending" {{ in_array($order->order_status, ['pending', 'menunggu']) ? 'selected' : '' }}>Menunggu Bayar</option>
                        <option value="dikemas" {{ $order->order_status == 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                        <option value="diantar" {{ in_array($order->order_status, ['diantar', 'dikirim']) ? 'selected' : '' }}>Diantar</option>
                        <option value="selesai" {{ $order->order_status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ in_array($order->order_status, ['dibatalkan', 'batal']) ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">Nomor Resi Pengiriman</label>
                    <input type="text"
                           name="tracking_number"
                           value="{{ old('tracking_number', $order->tracking_number) }}"
                           placeholder="Contoh: JNE10928301928"
                           class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3.5 py-2.5 text-xs font-mono text-[#24201D] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
                </div>

                <button type="submit"
                        class="w-full bg-[#3A4A3B] hover:bg-[#2C392D] text-white py-2.5 rounded-xl font-semibold text-xs transition shadow-2xs">
                    Simpan Perubahan
                </button>
            </form>
        </div>

    </div>
</div>
@endsection