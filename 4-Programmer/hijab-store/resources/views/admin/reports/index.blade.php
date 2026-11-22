@extends('layouts.admin')

@section('title', 'Rekap Penjualan')

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-7xl mx-auto space-y-6">

    <!-- Header & Tombol Ekspor -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Rekap Penjualan</h1>
                <span class="bg-[#3A4A3B]/10 text-[#3A4A3B] text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                    Khusus Pemilik
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-1">Laporan pendapatan transaksi terverifikasi lunas berdasarkan periode tanggal.</p>
        </div>

        <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
           class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-2xs flex items-center gap-2 w-fit shrink-0">
            <span>↓ Unduh CSV / Excel</span>
        </a>
    </div>

    <!-- Filter Periode Tanggal -->
    <form action="{{ route('admin.reports.index') }}" method="GET"
          class="bg-white p-4 sm:p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}"
                   class="bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3.5 py-2 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
        </div>

        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}"
                   class="bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3.5 py-2 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
        </div>

        <div class="flex items-center gap-2">
            <button type="submit"
                    class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition">
                Tampilkan Laporan
            </button>
            <a href="{{ route('admin.reports.index') }}"
               class="px-4 py-2.5 rounded-xl border border-[#D6C7B2] text-xs font-semibold text-gray-600 hover:bg-[#FAF7F2] transition">
                Bulan Ini
            </a>
        </div>
    </form>

    <!-- 4 Kartu Ringkasan Pendapatan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)]">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Pendapatan</p>
            <h3 class="font-serif text-2xl font-bold text-[#3A4A3B] mt-1">
                Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
            </h3>
            <p class="text-[11px] text-gray-400 mt-1.5">Status pembayaran lunas</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)]">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Transaksi</p>
            <h3 class="font-serif text-2xl font-bold text-[#24201D] mt-1">
                {{ $summary['total_orders'] }} <span class="text-xs font-sans font-normal text-gray-400">Pesanan</span>
            </h3>
            <p class="text-[11px] text-gray-400 mt-1.5">Dalam periode terpilih</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)]">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Produk Terjual</p>
            <h3 class="font-serif text-2xl font-bold text-[#24201D] mt-1">
                {{ $summary['total_items'] }} <span class="text-xs font-sans font-normal text-gray-400">Pcs</span>
            </h3>
            <p class="text-[11px] text-gray-400 mt-1.5">Akumulasi unit keluar</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)]">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Rata-rata Nilai Order</p>
            <h3 class="font-serif text-2xl font-bold text-[#24201D] mt-1">
                Rp {{ number_format($summary['average_order'], 0, ',', '.') }}
            </h3>
            <p class="text-[11px] text-gray-400 mt-1.5">Per transaksi berhasil</p>
        </div>
    </div>

    <!-- Tabel Rincian Penjualan -->
    <div class="bg-white rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] overflow-hidden">
        <div class="px-6 py-4 bg-[#FAF7F2]/60 border-b border-[#D6C7B2]/40 flex items-center justify-between">
            <h2 class="font-serif text-lg font-bold text-[#24201D]">Rincian Transaksi Penjualan</h2>
            <span class="text-[11px] font-mono text-gray-500">{{ $startDate }} s/d {{ $endDate }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#D6C7B2]/40 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-[#FAF7F2]/50">
                        <th class="py-4 px-6">NO. PESANAN</th>
                        <th class="py-4 px-6">TANGGAL</th>
                        <th class="py-4 px-6">PEMBELI</th>
                        <th class="py-4 px-6 text-center">ITEM</th>
                        <th class="py-4 px-6">STATUS</th>
                        <th class="py-4 px-6 text-right">NOMINAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3EFE6] text-xs text-[#24201D]">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                            <tr class="hover:bg-[#FAF7F2]/60 transition">
                                <td class="py-4 px-6 font-mono font-semibold text-[#24201D]">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                        #{{ $order->id }}
                                    </a>
                                </td>

                                <td class="py-4 px-6 text-gray-600">
                                    {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                </td>

                                <td class="py-4 px-6">
                                    <span class="font-semibold text-[#24201D] block">{{ $order->customer_name }}</span>
                                    <span class="text-[10px] text-gray-400 uppercase">{{ $order->courier ?? 'JNE' }}</span>
                                </td>

                                <td class="py-4 px-6 text-center font-semibold">
                                    {{ $order->items->sum('quantity') }} pcs
                                </td>

                                <td class="py-4 px-6">
                                    <span class="inline-block bg-[#DCFCE7] text-[#15803D] px-3 py-1 rounded-full font-semibold text-[10px] capitalize">
                                        {{ $order->order_status ?? 'Lunas' }}
                                    </span>
                                </td>

                                <td class="py-4 px-6 text-right font-semibold text-[#24201D]">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400 font-serif text-base">
                                Belum ada transaksi lunas pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endif
                </tbody>
                @if($orders->count() > 0)
                    <tfoot>
                        <tr class="bg-[#FAF7F2] border-t border-[#D6C7B2]/60 text-xs font-bold text-[#24201D]">
                            <td colspan="3" class="py-4 px-6">TOTAL PERIODE TERPILIH</td>
                            <td class="py-4 px-6 text-center">{{ $summary['total_items'] }} pcs</td>
                            <td></td>
                            <td class="py-4 px-6 text-right font-serif text-base text-[#3A4A3B]">
                                Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>
@endsection