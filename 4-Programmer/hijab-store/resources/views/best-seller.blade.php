@extends('layouts.app')

@section('content')
<div class="bg-[#FAF7F2] text-[#24201D] min-h-screen py-10 md:py-16">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Hero Header Halaman -->
        <div class="max-w-2xl mb-14">
            <h1 class="font-serif text-4xl md:text-5xl text-[#24201D] font-normal tracking-tight mb-4 leading-tight">
                Shea Daily <span class="italic text-[#3A4A3B]">Best Seller</span> Collection
            </h1>
            <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                Pilihan favorit <strong class="text-[#24201D]">#SheaBabes</strong> di seluruh Indonesia. Terbukti nyaman, tegak rapi sepanjang hari, dan tidak mudah kusut—didesain khusus untuk menyempurnakan hari aktif perempuan modern tanpa kompromi.
            </p>
        </div>

        <!-- Section Header: 4 Pilihan Utama Terlaris -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end border-b border-[#D6C7B2]/40 pb-6 mb-10 gap-4">
            <div>
                <p class="text-[10px] tracking-[0.2em] uppercase text-[#8C9B86] font-bold mb-1.5">PALING DIMINATI</p>
                <h2 class="font-serif text-3xl text-[#24201D] font-normal">4 Pilihan Utama Terlaris</h2>
            </div>
            <p class="text-[11px] text-gray-500 max-w-xs md:text-right leading-relaxed">
                Dikerjakan dengan jahitan tepi premium dan material breathable kelas dunia untuk kenyamanan berhijab setiap hari.
            </p>
        </div>

        <!-- Grid 4 Kartu Produk Terlaris -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @if($bestSellers->count() > 0)
                @foreach($bestSellers as $index => $product)
                    @php
                        $firstVariantWithImage = $product->variants->first(fn($v) => !empty($v->image));
                        $displayImage = $product->image ?: ($firstVariantWithImage ? $firstVariantWithImage->image : null);
                    @endphp

                    <div class="bg-white rounded-3xl border border-[#D6C7B2]/45 p-4 shadow-[0_2px_15px_-4px_rgba(36,32,29,0.05)] group flex flex-col justify-between transition hover:border-[#8C9B86]">
                        <div>
                            <!-- Bingkai Foto Produk -->
                            <div class="relative aspect-[3/4] rounded-2xl overflow-hidden bg-[#EFECE6] mb-4">
                                <span class="absolute top-3 left-3 bg-[#3A4A3B]/95 backdrop-blur-xs text-white text-[10px] font-semibold px-3 py-1 rounded-full z-10 shadow-2xs flex items-center gap-1">
                                    <span>★</span>
                                    <span>Terlaris #{{ $index + 1 }}</span>
                                </span>

                                <a href="{{ route('produk.detail', $product->slug) }}" class="block w-full h-full">
                                    @if($displayImage)
                                        <img src="{{ asset('storage/' . $displayImage) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 text-xs">
                                            <span>No Image</span>
                                        </div>
                                    @endif
                                </a>

                                <span class="absolute bottom-3 right-3 bg-[#24201D]/75 backdrop-blur-xs text-white text-[9px] font-medium px-2.5 py-1 rounded-full z-10">
                                    {{ $product->category->name ?? 'Premium Series' }} • {{ $product->weight }} gr
                                </span>
                            </div>

                            <!-- Nama Produk & Deskripsi Singkat -->
                            <div class="px-1">
                                <a href="{{ route('produk.detail', $product->slug) }}">
                                    <h3 class="font-serif text-lg text-[#24201D] group-hover:text-[#3A4A3B] transition leading-snug mb-2 line-clamp-2">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                <p class="text-[11px] text-gray-500 leading-relaxed line-clamp-3 mb-4">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($product->description), 95) }}
                                </p>
                            </div>
                        </div>

                        <!-- Harga Spesial & 2 Tombol Aksi -->
                        <div class="px-1 pt-2">
                            <p class="text-[9px] uppercase tracking-widest text-[#8C9B86] font-bold mb-0.5">HARGA SPESIAL</p>
                            <p class="font-serif text-xl font-bold text-[#24201D] mb-4">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>

                            <div class="space-y-2">
                                <a href="{{ route('produk.detail', $product->slug) }}"
                                   class="w-full bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-[11px] font-semibold py-2.5 rounded-full transition flex items-center justify-center gap-1.5 shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span>Beli Sekarang</span>
                                </a>

                                <a href="{{ route('produk.detail', $product->slug) }}"
                                   class="w-full bg-[#F3EFE6] hover:bg-[#D6C7B2]/50 text-[#24201D] text-[11px] font-medium py-2.5 rounded-full transition flex items-center justify-center gap-1.5">
                                    <span>«</span>
                                    <span>Tambah ke Keranjang</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-[#D6C7B2]/40">
                    <p class="font-serif text-lg text-[#24201D] mb-1">Belum Ada Produk Terlaris</p>
                    <p class="text-xs text-gray-500">Koleksi terlaris akan tampil otomatis di sini.</p>
                </div>
            @endif
        </div>

    </section>
</div>
@endsection