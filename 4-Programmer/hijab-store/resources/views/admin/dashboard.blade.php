@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Dashboard</h1>
            <p class="text-xs text-gray-500 mt-1">Ringkasan operasional toko, status pesanan, dan pantauan stok menipis Shea Daily.</p>
        </div>
        <div class="text-xs font-mono text-gray-500 bg-white px-4 py-2 rounded-xl border border-[#D6C7B2]/60">
            {{ now()->timezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- 4 Kartu Metrik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)]">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pesanan Hari Ini</p>
            <h3 class="font-serif text-3xl font-bold text-[#24201D] mt-1">{{ $stats['orders_today'] }}</h3>
            <p class="text-[11px] text-gray-400 mt-2">Transaksi masuk hari ini</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)]">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Perlu Dikemas</p>
            <h3 class="font-serif text-3xl font-bold text-amber-700 mt-1">{{ $stats['to_pack'] }}</h3>
            <a href="{{ route('admin.orders.index', ['status' => 'dikemas']) }}" class="text-[11px] text-[#3A4A3B] font-semibold hover:underline mt-2 inline-block">
                Lihat pesanan dikemas →
            </a>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)]">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Stok Menipis (≤ 5)</p>
            <h3 class="font-serif text-3xl font-bold text-red-600 mt-1">{{ $stats['low_stock_count'] }} <span class="text-xs font-sans font-normal text-gray-400">Varian</span></h3>
            <a href="{{ route('admin.stocks.index') }}" class="text-[11px] text-[#3A4A3B] font-semibold hover:underline mt-2 inline-block">
                Kelola stok varian →
            </a>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)]">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Omzet Bulan Ini (Lunas)</p>
            <h3 class="font-serif text-2xl font-bold text-[#3A4A3B] mt-1">
                Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}
            </h3>
            <p class="text-[11px] text-gray-400 mt-2">Total {{ $stats['total_products'] }} produk aktif</p>
        </div>
    </div>

    <!-- Grid 2 Kolom: Pesanan Terbaru & Peringatan Stok Menipis -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Pesanan Terbaru (2 Kolom) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] overflow-hidden">
            <div class="px-6 py-4 bg-[#FAF7F2]/60 border-b border-[#D6C7B2]/40 flex items-center justify-between">
                <h2 class="font-serif text-lg font-bold text-[#24201D]">Pesanan Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-[#3A4A3B] hover:underline">Semua Pesanan →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#D6C7B2]/40 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-[#FAF7F2]/30">
                            <th class="py-3 px-6">NO. PESANAN</th>
                            <th class="py-3 px-6">PEMBELI</th>
                            <th class="py-3 px-6">TOTAL</th>
                            <th class="py-3 px-6">STATUS</th>
                            <th class="py-3 px-6 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3EFE6] text-xs">
                        @if($recentOrders->count() > 0)
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-[#FAF7F2]/60 transition">
                                    <td class="py-3.5 px-6 font-mono font-semibold text-[#24201D]">#{{ $order->id }}</td>
                                    <td class="py-3.5 px-6">
                                        <span class="font-semibold text-[#24201D] block">{{ $order->customer_name }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</span>
                                    </td>
                                    <td class="py-3.5 px-6 font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-6">
                                        <span class="inline-block bg-[#FAF7F2] border border-[#D6C7B2] text-[#24201D] px-2.5 py-0.5 rounded-full text-[10px] font-semibold capitalize">
                                            {{ $order->order_status ?? 'pending' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 text-right">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-xs font-semibold text-[#3A4A3B] hover:underline">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 font-serif">Belum ada pesanan masuk.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Peringatan Stok Menipis (1 Kolom) -->
        <div class="bg-white rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] overflow-hidden h-fit">
            <div class="px-6 py-4 bg-[#FAF7F2]/60 border-b border-[#D6C7B2]/40 flex items-center justify-between">
                <h2 class="font-serif text-lg font-bold text-[#24201D]">Stok Menipis</h2>
                <a href="{{ route('admin.stocks.index') }}" class="text-xs font-semibold text-[#3A4A3B] hover:underline">Kelola →</a>
            </div>
            <div class="divide-y divide-[#F3EFE6] px-6">
                @if($lowStockVariants->count() > 0)
                    @foreach($lowStockVariants as $variant)
                        <div class="py-3.5 flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0">
                                <p class="font-semibold text-[#24201D] truncate">{{ $variant->product->name ?? 'Produk' }}</p>
                                <p class="text-[11px] text-gray-400">
                                    {{ $variant->color }} {{ $variant->size ? '• ' . $variant->size : '' }}
                                </p>
                            </div>
                            <span class="{{ $variant->stock == 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800' }} px-2.5 py-1 rounded-full font-mono font-bold text-[11px] shrink-0">
                                {{ $variant->stock }} pcs
                            </span>
                        </div>
                    @endforeach
                @else
                    <p class="py-8 text-center text-xs text-gray-400">Semua stok varian dalam kondisi aman (> 5 pcs).</p>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection