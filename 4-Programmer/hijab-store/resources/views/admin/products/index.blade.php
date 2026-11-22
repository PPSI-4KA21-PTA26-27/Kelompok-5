@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-7xl mx-auto space-y-6">

    <!-- Header & Action Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Produk</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola katalog busana dan stok hijab aktif.</p>
        </div>

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.products.index') }}" method="GET" class="relative">
                <span class="absolute left-3.5 top-2.5 text-gray-400 text-xs">🔍</span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari produk..."
                       class="bg-white border border-[#D6C7B2]/70 rounded-xl pl-9 pr-4 py-2 text-xs text-[#24201D] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] w-56 sm:w-64">
            </form>

            <a href="{{ route('admin.products.create') }}"
               class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-2xs flex items-center gap-1.5 shrink-0">
                <span>+ Tambah</span>
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-[#D1FAE5] border border-[#A7F3D0] text-[#065F46] px-4 py-3 rounded-xl text-xs font-medium flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-[#065F46] font-bold text-sm">×</button>
        </div>
    @endif

    <!-- Tabel Daftar Produk -->
    <div class="bg-white rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#D6C7B2]/40 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-[#FAF7F2]/50">
                        <th class="py-4 px-6 w-20">FOTO</th>
                        <th class="py-4 px-6">NAMA PRODUK</th>
                        <th class="py-4 px-6">KATEGORI</th>
                        <th class="py-4 px-6">HARGA</th>
                        <th class="py-4 px-6">STATUS</th>
                        <th class="py-4 px-6 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3EFE6] text-xs text-[#24201D]">
                    @forelse($products as $product)
                        @php
                            $firstVariantWithImage = $product->variants->first(fn($v) => !empty($v->image));
                            $displayImage = $product->image ?: ($firstVariantWithImage ? $firstVariantWithImage->image : null);
                            $totalStock = $product->variants->sum('stock');
                            $variantSummary = $product->variants->take(3)->pluck('color')->implode(', ');
                            if ($product->variants->count() > 3) {
                                $variantSummary .= ' +' . ($product->variants->count() - 3);
                            }
                        @endphp
                        <tr class="hover:bg-[#FAF7F2]/60 transition">
                            <!-- Foto -->
                            <td class="py-4 px-6">
                                @if($displayImage)
                                    <img src="{{ asset('storage/' . $displayImage) }}" alt="{{ $product->name }}" class="w-11 h-11 object-cover rounded-xl border border-[#D6C7B2]/50">
                                @else
                                    <div class="w-11 h-11 rounded-xl bg-[#F3EFE6] border border-[#D6C7B2]/50 flex items-center justify-center text-[10px] text-gray-400">
                                        No Pic
                                    </div>
                                @endif
                            </td>

                            <!-- Nama Produk & Ringkasan Varian -->
                            <td class="py-4 px-6">
                                <span class="font-semibold text-[#24201D] text-xs block">{{ $product->name }}</span>
                                <span class="text-[11px] text-gray-400 block mt-0.5">
                                    {{ $variantSummary ?: 'Belum ada varian' }} • Stok: {{ $totalStock }} pcs
                                </span>
                            </td>

                            <!-- Kategori -->
                            <td class="py-4 px-6 text-gray-600">
                                {{ $product->category->name ?? 'Tanpa Kategori' }}
                            </td>

                            <!-- Harga -->
                            <td class="py-4 px-6 font-semibold text-[#24201D]">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <span class="inline-block bg-[#DCFCE7] text-[#15803D] px-3 py-1 rounded-full font-semibold text-[10px]">
                                    Tampil
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-3 text-xs font-semibold">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-[#24201D] hover:text-[#5A6D57] transition">
                                        Ubah
                                    </a>
                                    <span class="text-gray-300">•</span>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400 font-serif text-base">
                                Belum ada produk hijab yang tersimpan di katalog.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        <div class="px-6 py-4 bg-[#FAF7F2]/50 border-t border-[#D6C7B2]/40 flex items-center justify-between text-xs text-gray-500">
            <span>Menampilkan <strong>{{ $products->count() }}</strong> produk</span>
        </div>
    </div>

</div>
@endsection