@extends('layouts.admin')

@section('title', 'Pesanan')

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-7xl mx-auto space-y-6">

    <!-- Header & Search Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Pesanan</h1>
            <p class="text-xs text-gray-500 mt-1">Verifikasi bukti transfer manual, kelola status pengiriman, dan input nomor resi.</p>
        </div>

        <form action="{{ route('admin.orders.index') }}" method="GET" class="relative">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <span class="absolute left-3.5 top-2.5 text-gray-400 text-xs">🔍</span>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nomor pesanan atau nama..."
                   class="bg-white border border-[#D6C7B2]/70 rounded-xl pl-9 pr-4 py-2 text-xs text-[#24201D] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] w-64 sm:w-72">
        </form>
    </div>

    <!-- Alert Success Message -->
    @if(session('success'))
        <div class="bg-[#D1FAE5] border border-[#A7F3D0] text-[#065F46] px-4 py-3 rounded-xl text-xs font-medium flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#065F46] font-bold text-sm">×</button>
        </div>
    @endif

    <!-- Filter Tab Status Bar -->
    @php
        $currentStatus = request('status', '');
    @endphp
    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => ''])) }}"
           class="{{ $currentStatus == '' ? 'bg-[#3A4A3B] text-white shadow-2xs' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full transition">
            Semua ({{ $counts['all'] ?? $orders->count() }})
        </a>

        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'unpaid'])) }}"
           class="{{ $currentStatus == 'unpaid' ? 'bg-[#3A4A3B] text-white shadow-2xs' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full transition">
            Menunggu Bayar ({{ $counts['unpaid'] ?? 0 }})
        </a>

        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'dikemas'])) }}"
           class="{{ $currentStatus == 'dikemas' ? 'bg-[#3A4A3B] text-white shadow-2xs' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full transition">
            Dikemas ({{ $counts['dikemas'] ?? 0 }})
        </a>

        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'diantar'])) }}"
           class="{{ $currentStatus == 'diantar' ? 'bg-[#3A4A3B] text-white shadow-2xs' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full transition">
            Diantar ({{ $counts['diantar'] ?? 0 }})
        </a>

        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'selesai'])) }}"
           class="{{ $currentStatus == 'selesai' ? 'bg-[#3A4A3B] text-white shadow-2xs' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full transition">
            Selesai ({{ $counts['selesai'] ?? 0 }})
        </a>

        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'dibatalkan'])) }}"
           class="{{ $currentStatus == 'dibatalkan' ? 'bg-[#3A4A3B] text-white shadow-2xs' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full transition">
            Dibatalkan ({{ $counts['dibatalkan'] ?? 0 }})
        </a>
    </div>

    <!-- Tabel Daftar Pesanan -->
    <div class="bg-white rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#D6C7B2]/40 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-[#FAF7F2]/50">
                        <th class="py-4 px-6">NO. PESANAN</th>
                        <th class="py-4 px-6">TANGGAL</th>
                        <th class="py-4 px-6">PEMBELI</th>
                        <th class="py-4 px-6">TOTAL</th>
                        <th class="py-4 px-6">METODE BAYAR</th>
                        <th class="py-4 px-6">STATUS</th>
                        <th class="py-4 px-6 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3EFE6] text-xs text-[#24201D]">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                            <tr class="hover:bg-[#FAF7F2]/60 transition">
                                <!-- No Pesanan -->
                                <td class="py-4 px-6">
                                    <span class="font-mono font-semibold text-xs text-[#24201D] block">#{{ $order->id }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $order->items->sum('quantity') }} item</span>
                                </td>

                                <!-- Tanggal -->
                                <td class="py-4 px-6 text-gray-600">
                                    <span class="block">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</span>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $order->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</span>
                                </td>

                                <!-- Pembeli -->
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-[#24201D] block">{{ $order->customer_name }}</span>
                                    <span class="text-[11px] text-gray-400 font-mono">{{ $order->customer_phone ?? '-' }}</span>
                                </td>

                                <!-- Total -->
                                <td class="py-4 px-6 font-semibold text-[#24201D]">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>

                                <!-- Metode Bayar -->
                                <td class="py-4 px-6 text-gray-600">
                                    <span class="font-medium text-[#24201D] block">Midtrans</span>
                                    <span class="text-[10px] text-gray-400 uppercase">{{ $order->courier ?? 'JNE' }}</span>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-6">
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
                                          class="inline-block px-3 py-1 rounded-full font-semibold text-[10px]">
                                        {{ $statusStyle['label'] }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="inline-block bg-white border border-[#D6C7B2] hover:bg-[#3A4A3B] hover:text-white hover:border-[#3A4A3B] text-[#24201D] px-3.5 py-1.5 rounded-xl text-xs font-semibold transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400 font-serif text-base">
                                Belum ada pesanan masuk yang tercatat.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        <div class="px-6 py-4 bg-[#FAF7F2]/50 border-t border-[#D6C7B2]/40 flex justify-between items-center text-xs text-gray-500">
            <span>Menampilkan <strong>{{ $orders->count() }}</strong> pesanan</span>
        </div>
    </div>

</div>
@endsection