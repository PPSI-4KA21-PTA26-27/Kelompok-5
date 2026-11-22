@extends('layouts.admin')

@section('title', 'Stok per Varian')

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-7xl mx-auto space-y-6">

    <!-- Header & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Stok per Varian</h1>
            <p class="text-xs text-gray-500 mt-1">Pembaruan cepat jumlah stok persediaan berdasarkan SKU, warna, dan ukuran.</p>
        </div>

        <form action="{{ route('admin.stocks.index') }}" method="GET" class="relative">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <span class="absolute left-3.5 top-2.5 text-gray-400 text-xs">🔍</span>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari SKU, produk, atau warna..."
                   class="bg-white border border-[#D6C7B2]/70 rounded-xl pl-9 pr-4 py-2 text-xs text-[#24201D] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] w-64 sm:w-72">
        </form>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-[#D1FAE5] border border-[#A7F3D0] text-[#065F46] px-4 py-3 rounded-xl text-xs font-medium flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#065F46] font-bold text-sm">×</button>
        </div>
    @endif

    <!-- Tab Filter Kondisi Stok -->
    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
        <a href="{{ route('admin.stocks.index') }}"
           class="{{ !request('filter') ? 'bg-[#3A4A3B] text-white' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full transition">
            Semua Varian
        </a>
        <a href="{{ route('admin.stocks.index', ['filter' => 'low']) }}"
           class="{{ request('filter') === 'low' ? 'bg-[#3A4A3B] text-white' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full transition">
            Stok Menipis (≤ 5)
        </a>
        <a href="{{ route('admin.stocks.index', ['filter' => 'empty']) }}"
           class="{{ request('filter') === 'empty' ? 'bg-[#3A4A3B] text-white' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full transition">
            Stok Habis (0)
        </a>
    </div>

    <!-- Tabel Stok Varian -->
    <div class="bg-white rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#D6C7B2]/40 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-[#FAF7F2]/50">
                        <th class="py-4 px-6">KODE SKU</th>
                        <th class="py-4 px-6">PRODUK & KATEGORI</th>
                        <th class="py-4 px-6">WARNA & UKURAN</th>
                        <th class="py-4 px-6">STATUS STOK</th>
                        <th class="py-4 px-6 text-right">UPDATE JUMLAH STOK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3EFE6] text-xs text-[#24201D]">
                    @if($variants->count() > 0)
                        @foreach($variants as $variant)
                            <tr class="hover:bg-[#FAF7F2]/60 transition">
                                <td class="py-4 px-6 font-mono text-[11px] text-gray-500">
                                    {{ $variant->sku ?? '-' }}
                                </td>

                                <td class="py-4 px-6">
                                    <span class="font-semibold text-[#24201D] block">{{ $variant->product->name ?? 'Produk Dihapus' }}</span>
                                    <span class="text-[11px] text-gray-400">{{ $variant->product->category->name ?? 'Tanpa Kategori' }}</span>
                                </td>

                                <td class="py-4 px-6">
                                    <span class="font-medium text-[#24201D]">{{ $variant->color }}</span>
                                    @if($variant->size)
                                        <span class="text-gray-400"> • {{ $variant->size }}</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6">
                                    @if($variant->stock == 0)
                                        <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full font-semibold text-[10px]">Habis</span>
                                    @elseif($variant->stock <= 5)
                                        <span class="inline-block bg-amber-100 text-amber-800 px-3 py-1 rounded-full font-semibold text-[10px]">Menipis ({{ $variant->stock }})</span>
                                    @else
                                        <span class="inline-block bg-[#DCFCE7] text-[#15803D] px-3 py-1 rounded-full font-semibold text-[10px]">Tersedia ({{ $variant->stock }})</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <form action="{{ route('admin.stocks.update', $variant->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="stock" value="{{ $variant->stock }}" min="0" required
                                               class="w-20 bg-[#FAF7F2] border border-[#D6C7B2] rounded-xl px-2.5 py-1.5 text-xs text-center font-mono font-semibold text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
                                        <button type="submit"
                                                class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold px-3.5 py-1.5 rounded-xl transition">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400 font-serif text-base">
                                Tidak ada varian produk yang ditemukan.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-[#FAF7F2]/50 border-t border-[#D6C7B2]/40 flex justify-between items-center text-xs text-gray-500">
            <span>Menampilkan <strong>{{ $variants->count() }}</strong> varian produk</span>
        </div>
    </div>

</div>
@endsection