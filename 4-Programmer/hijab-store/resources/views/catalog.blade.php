@extends('layouts.app')

@section('content')
<div class="bg-[#FAF7F2] text-[#24201D] min-h-screen py-8 md:py-12">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="flex items-center text-[10px] tracking-[0.18em] uppercase text-[#8C9B86] font-semibold mb-4 space-x-2">
            <a href="{{ url('/') }}" class="hover:text-[#5A6D57] transition">HOME</a>
            <span>/</span>
            <a href="{{ route('katalog.index') }}" class="hover:text-[#5A6D57] transition {{ !$activeCategory ? 'text-[#24201D]' : '' }}">KATALOG</a>
            @if($activeCategory)
                <span>/</span>
                <span class="text-[#24201D]">{{ strtoupper($activeCategory->name) }}</span>
            @endif
        </nav>

        <!-- Header Halaman -->
        <div class="max-w-2xl mb-6">
            <h1 class="font-serif text-3xl md:text-4xl text-[#24201D] font-normal tracking-tight mb-2.5">
                {{ $activeCategory ? 'Koleksi ' . $activeCategory->name : 'Katalog Hijab & Modest Wear' }}
            </h1>
            <p class="text-xs text-gray-500 leading-relaxed">
                Koleksi hijab harian berkualitas tinggi dengan kenyamanan paripurna. Dirancang untuk siluet modern tanpa kompromi syar'i.
            </p>
        </div>

        <!-- Tab Filter Kategori (Sesuai SDD P-02) -->
        @if(isset($categories) && $categories->count() > 0)
            <div class="flex flex-wrap items-center gap-2 mb-6">
                <a href="{{ route('katalog.index', request()->only(['search', 'sort'])) }}"
                   class="{{ !$activeCategory ? 'bg-[#3A4A3B] text-white shadow-2xs' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full text-xs font-semibold transition">
                    Semua Kategori
                </a>

                @foreach($categories as $category)
                    @php
                        $isActiveCat = $activeCategory && $activeCategory->id === $category->id;
                    @endphp
                    <a href="{{ route('katalog.index', array_merge(request()->only(['search', 'sort']), ['category' => $category->slug])) }}"
                       class="{{ $isActiveCat ? 'bg-[#3A4A3B] text-white shadow-2xs' : 'bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#F3EFE6]' }} px-4 py-2 rounded-full text-xs font-semibold transition">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Bar Filter & Urutkan (Sort Bar) -->
        <div class="bg-[#F3EFE6]/90 border border-[#D6C7B2]/50 rounded-2xl px-5 py-3.5 mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <p class="text-xs text-gray-600">
                Menampilkan:
                <strong class="text-[#24201D]">
                    @if(method_exists($products, 'firstItem') && $products->total() > 0)
                        {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} Produk
                    @else
                        {{ $products->count() }} Produk
                    @endif
                </strong>
            </p>

            <!-- Form Pencarian & Pengurutan -->
            <form action="{{ route('katalog.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <!-- Search Input Ringkas -->
                <div class="relative flex-1 sm:w-48">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama hijab..."
                           class="w-full text-xs bg-white border border-[#D6C7B2]/70 rounded-xl pl-3 pr-8 py-2 text-[#24201D] focus:ring-[#3A4A3B] focus:border-[#3A4A3B]">
                    <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#3A4A3B]">
                        🔍
                    </button>
                </div>

                <!-- Dropdown Urutkan -->
                <div class="flex items-center gap-2">
                    <label for="sort" class="text-[10px] font-bold uppercase tracking-wider text-gray-500">URUTKAN:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()"
                            class="text-xs bg-white border border-[#D6C7B2]/70 rounded-xl px-3.5 py-2 text-[#24201D] font-medium focus:ring-[#3A4A3B] focus:border-[#3A4A3B] cursor-pointer">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru (New Arrivals)</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A - Z</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Grid Katalog Produk (4 Kolom x 2 Baris) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @if($products->count() > 0)
                @foreach($products as $product)
                    @php
                        $firstVariantWithImage = $product->variants->first(fn($v) => !empty($v->image));
                        $displayImage = $product->image ?: ($firstVariantWithImage ? $firstVariantWithImage->image : null);
                    @endphp

                    <div class="bg-white rounded-3xl border border-[#D6C7B2]/45 p-3.5 shadow-[0_2px_14px_-4px_rgba(36,32,29,0.05)] group flex flex-col justify-between transition hover:border-[#8C9B86]">
                        <div>
                            <!-- Bingkai Foto Produk -->
                            <div class="relative aspect-[4/4.5] rounded-2xl overflow-hidden bg-[#EFECE6] mb-3.5">
                                @if($loop->index % 4 === 0)
                                    <span class="absolute top-3 left-3 bg-[#3A4A3B] text-white text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full z-10 shadow-2xs">NEW</span>
                                @elseif($loop->index % 4 === 1)
                                    <span class="absolute top-3 left-3 bg-[#5A6D57] text-white text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full z-10 shadow-2xs">MUST HAVE</span>
                                @elseif($loop->index % 4 === 2)
                                    <span class="absolute top-3 left-3 bg-[#8C9B86] text-white text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full z-10 shadow-2xs">RESTOCK</span>
                                @else
                                    <span class="absolute top-3 left-3 bg-[#3A4A3B] text-white text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full z-10 shadow-2xs">NEW</span>
                                @endif

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

                                <a href="{{ route('produk.detail', $product->slug) }}" title="Lihat Detail & Varian"
                                   class="absolute bottom-3 right-3 w-8 h-8 rounded-xl bg-white/95 backdrop-blur-xs border border-[#D6C7B2]/50 text-[#24201D] hover:bg-[#3A4A3B] hover:text-white flex items-center justify-center shadow-sm transition z-10">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </a>
                            </div>

                            <!-- Info Kategori & Berat -->
                            <div class="px-1">
                                <p class="text-[9px] uppercase tracking-[0.14em] text-[#8C9B86] font-bold mb-1 line-clamp-1">
                                    {{ strtoupper($product->category->name ?? 'MODEST SERIES') }} • {{ $product->weight }} GR
                                </p>

                                <!-- Nama Produk -->
                                <a href="{{ route('produk.detail', $product->slug) }}">
                                    <h3 class="font-serif text-base text-[#24201D] group-hover:text-[#3A4A3B] transition line-clamp-1 mb-2">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                <!-- Swatch Warna Bulat + Keterangan Jumlah Varian -->
                                <div class="flex items-center gap-1.5 mb-3">
                                    @foreach($product->variants->take(3) as $index => $variant)
                                        @php
                                            $swatchColors = ['bg-[#24201D]', 'bg-[#D6C7B2]', 'bg-[#8C9B86]'];
                                            $bgClass = $swatchColors[$index % 3];
                                        @endphp
                                        <span class="w-3 h-3 rounded-full border border-[#D6C7B2] {{ $bgClass }}" title="{{ $variant->color }}"></span>
                                    @endforeach
                                    <span class="text-[10px] text-gray-400 ml-1">
                                        +{{ $product->variants->count() }} Varian
                                    </span>
                                </div>

                                <!-- Harga Produk -->
                                <div class="flex items-baseline gap-2 mb-3.5">
                                    <span class="font-bold text-sm text-[#24201D]">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol CTA Full Width -->
                        <a href="{{ route('produk.detail', $product->slug) }}"
                           class="w-full bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-[11px] font-semibold uppercase tracking-wider py-2.5 rounded-xl transition flex items-center justify-center gap-1.5 shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>BELI INSTAN</span>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-[#D6C7B2]/40">
                    <p class="font-serif text-lg text-[#24201D] mb-1">Produk Tidak Ditemukan</p>
                    <p class="text-xs text-gray-500 mb-4">Coba gunakan kata kunci pencarian lain atau pilih kategori lainnya.</p>
                    <a href="{{ route('katalog.index') }}" class="inline-block bg-[#3A4A3B] text-white text-xs font-semibold px-5 py-2.5 rounded-xl">
                        Tampilkan Semua Produk
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination Bar Bawah -->
        @if(method_exists($products, 'hasPages') && $products->hasPages())
            <div class="mt-12 pt-6 border-t border-[#D6C7B2]/40 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-500">
                <p>
                    Menampilkan halaman <strong class="text-[#24201D]">{{ $products->currentPage() }}</strong> dari <strong class="text-[#24201D]">{{ $products->lastPage() }}</strong>
                </p>

                <div class="flex items-center gap-1.5">
                    @if ($products->onFirstPage())
                        <span class="w-8 h-8 rounded-lg bg-white/50 border border-[#D6C7B2]/40 text-gray-300 flex items-center justify-center cursor-not-allowed">‹</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="w-8 h-8 rounded-lg bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#3A4A3B] hover:text-white flex items-center justify-center transition">‹</a>
                    @endif

                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <span class="w-8 h-8 rounded-lg bg-[#3A4A3B] text-white font-semibold flex items-center justify-center shadow-2xs">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 rounded-lg bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#EFECE6] flex items-center justify-center transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="w-8 h-8 rounded-lg bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#3A4A3B] hover:text-white flex items-center justify-center transition">›</a>
                    @else
                        <span class="w-8 h-8 rounded-lg bg-white/50 border border-[#D6C7B2]/40 text-gray-300 flex items-center justify-center cursor-not-allowed">›</span>
                    @endif
                </div>
            </div>
        @endif

    </section>
</div>
@endsection