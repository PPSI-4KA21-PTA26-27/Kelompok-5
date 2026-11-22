@extends('layouts.app')

@section('content')
<div class="bg-[#FAF7F2] text-[#24201D]">

    <!-- 1. HERO BANNER DINAMIS DARI DATABASE -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">
        <div class="relative rounded-3xl overflow-hidden h-[460px] md:h-[520px] shadow-md group" id="hero-slider">

            @if(isset($banners) && $banners->count() > 0)
                @foreach($banners as $index => $banner)
                    <div class="hero-slide {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }} absolute inset-0 transition-opacity duration-700 ease-in-out">
                        <img src="{{ asset('storage/' . $banner->image) }}"
                             alt="{{ $banner->title }}"
                             class="w-full h-full object-cover object-center">

                        <div class="absolute inset-0 bg-gradient-to-r from-[#24201D]/80 via-[#24201D]/45 to-transparent"></div>

                        <div class="absolute inset-0 flex flex-col justify-center px-8 md:px-14 max-w-2xl text-white">
                            <div class="flex flex-wrap items-center gap-2 mb-5">
                                @if($banner->badge)
                                    <span class="bg-white text-[#24201D] text-[10px] font-semibold px-3 py-1 rounded-full tracking-wide">
                                        {{ $banner->badge }}
                                    </span>
                                @endif
                                <span class="bg-white/20 backdrop-blur-xs text-white border border-white/30 text-[10px] font-medium px-3 py-1 rounded-full">
                                    Bahan Adem & Ringan
                                </span>
                            </div>

                            <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl leading-[1.12] font-normal mb-4">
                                {{ $banner->title }}
                            </h1>

                            @if($banner->subtitle)
                                <p class="text-xs md:text-sm text-white/85 leading-relaxed max-w-md mb-7 font-light">
                                    {{ $banner->subtitle }}
                                </p>
                            @endif

                            <div class="flex flex-wrap items-center gap-3">
                                <a href="{{ $banner->button_link ?: route('katalog.index') }}" class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold uppercase px-6 py-3 rounded-full transition shadow-sm flex items-center gap-2">
                                    <span>{{ $banner->button_text ?: 'JELAJAHI KOLEKSI' }}</span>
                                    <span>→</span>
                                </a>
                                <a href="#lacak-paket" class="bg-white/15 hover:bg-white/25 backdrop-blur-xs border border-white/30 text-white text-xs font-medium px-5 py-3 rounded-full transition flex items-center gap-2">
                                    <span>🚚</span>
                                    <span>Gratis Ongkir Min. 250rb</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($banners->count() > 1)
                    <button type="button" id="hero-prev" class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur-xs text-white items-center justify-center transition opacity-0 group-hover:opacity-100">
                        ‹
                    </button>
                    <button type="button" id="hero-next" class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur-xs text-white items-center justify-center transition opacity-0 group-hover:opacity-100">
                        ›
                    </button>

                    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
                        @foreach($banners as $index => $banner)
                            <button type="button" data-slide="{{ $index }}"
                                    class="hero-dot h-2 rounded-full transition-all {{ $index === 0 ? 'w-6 bg-white' : 'w-2 bg-white/50 hover:bg-white/80' }}">
                            </button>
                        @endforeach
                    </div>
                @endif

            @else
                <img src="https://images.unsplash.com/photo-1589998059171-989d887dda1e?auto=format&fit=crop&w=1400&q=85"
                     alt="Koleksi Esensial Harian Shea Daily"
                     class="w-full h-full object-cover object-center">

                <div class="absolute inset-0 bg-gradient-to-r from-[#24201D]/80 via-[#24201D]/45 to-transparent"></div>

                <div class="absolute inset-0 flex flex-col justify-center px-8 md:px-14 max-w-2xl text-white">
                    <div class="flex flex-wrap items-center gap-2 mb-5">
                        <span class="bg-white text-[#24201D] text-[10px] font-semibold px-3 py-1 rounded-full tracking-wide">
                            • Serene Modesty Collection 2026
                        </span>
                        <span class="bg-white/20 backdrop-blur-xs text-white border border-white/30 text-[10px] font-medium px-3 py-1 rounded-full">
                            Bahan Adem & Ringan
                        </span>
                    </div>

                    <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl leading-[1.12] font-normal mb-4">
                        Koleksi Esensial <br> Harian yang <br> Menenangkan
                    </h1>

                    <p class="text-xs md:text-sm text-white/85 leading-relaxed max-w-md mb-7 font-light">
                        Sentuhan voal murni dan pashmina sutra bernapas alami. Dirancang untuk keanggunan bersahaja, nyaman tegak tanpa repot sepanjang hari.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('katalog.index') }}" class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold px-6 py-3 rounded-full transition shadow-sm flex items-center gap-2">
                            <span>JELAJAHI KOLEKSI</span>
                            <span>→</span>
                        </a>
                        <a href="#lacak-paket" class="bg-white/15 hover:bg-white/25 backdrop-blur-xs border border-white/30 text-white text-xs font-medium px-5 py-3 rounded-full transition flex items-center gap-2">
                            <span>🚚</span>
                            <span>Gratis Ongkir Min. 250rb</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- 2. KOLEKSI TERBARU SHEA (4 KOLOM PRODUK) -->
    <section id="koleksi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
            <div>
                <p class="text-[10px] tracking-[0.2em] text-[#8C9B86] uppercase font-bold mb-1.5">— CURATED CAPSULE</p>
                <h2 class="font-serif text-3xl text-[#24201D]">Koleksi Terbaru Shea</h2>
            </div>

            <!-- Filter Pills -->
            <div class="flex items-center gap-2 text-xs overflow-x-auto pb-1">
                <a href="{{ route('katalog.index') }}" class="bg-[#3A4A3B] text-white px-4 py-1.5 rounded-full font-medium shadow-2xs">Semua</a>
                @if(isset($categories) && $categories->count() > 0)
                    @foreach($categories->take(3) as $cat)
                        <a href="{{ route('katalog.index', ['category' => $cat->slug]) }}"
                           class="bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#EFECE6] px-4 py-1.5 rounded-full transition whitespace-nowrap">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('katalog.index', ['search' => 'Voal']) }}" class="bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#EFECE6] px-4 py-1.5 rounded-full transition">Voal Square</a>
                    <a href="{{ route('katalog.index', ['search' => 'Pashmina']) }}" class="bg-white border border-[#D6C7B2]/60 text-[#24201D] hover:bg-[#EFECE6] px-4 py-1.5 rounded-full transition">Silk Pashmina</a>
                @endif
            </div>
        </div>

        <!-- Grid Kartu Produk -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @if($products->count() > 0)
                @foreach($products as $product)
                    @php
                        $firstVariantWithImage = $product->variants->first(fn($v) => !empty($v->image));
                        $displayImage = $product->image ?: ($firstVariantWithImage ? $firstVariantWithImage->image : null);
                    @endphp

                    <div class="bg-white rounded-2xl border border-[#D6C7B2]/40 overflow-hidden shadow-[0_2px_12px_-4px_rgba(36,32,29,0.05)] group flex flex-col justify-between">
                        <div>
                            <div class="relative aspect-[4/4] bg-[#EFECE6] overflow-hidden">
                                @if($loop->index === 0)
                                    <span class="absolute top-3 left-3 bg-[#3A4A3B] text-white text-[10px] font-semibold px-2.5 py-0.5 rounded-full z-10">Best Pick</span>
                                @elseif($loop->index === 1)
                                    <span class="absolute top-3 left-3 bg-[#8C9B86] text-white text-[10px] font-semibold px-2.5 py-0.5 rounded-full z-10">Trending</span>
                                @elseif($loop->index === 3)
                                    <span class="absolute top-3 left-3 bg-[#B87C4C] text-white text-[10px] font-semibold px-2.5 py-0.5 rounded-full z-10">Terbatas</span>
                                @endif

                                <a href="{{ route('produk.detail', $product->slug) }}" class="block w-full h-full">
                                    @if($displayImage)
                                        <img src="{{ asset('storage/' . $displayImage) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 text-xs">
                                            <span>No Image</span>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <div class="p-4 pb-2">
                                <p class="text-[9px] uppercase tracking-widest text-[#8C9B86] font-semibold mb-1">
                                    {{ strtoupper($product->category->name ?? 'PREMIUM HIJAB SERIES') }}
                                </p>
                                <a href="{{ route('produk.detail', $product->slug) }}">
                                    <h3 class="font-serif text-base text-[#24201D] group-hover:text-[#3A4A3B] transition line-clamp-1 mb-2">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                <div class="flex items-center gap-1.5 mb-3">
                                    @foreach($product->variants->take(4) as $variant)
                                        <span class="w-3 h-3 rounded-full border border-[#D6C7B2] bg-[#D6C7B2]/60" title="{{ $variant->color }}"></span>
                                    @endforeach
                                    @if($product->variants->count() > 0)
                                        <span class="text-[10px] text-gray-400 ml-1">+{{ $product->variants->count() }} varian</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-4 pb-4 pt-2 border-t border-[#FAF7F2] flex items-center justify-between">
                            <div>
                                <p class="font-bold text-sm text-[#24201D]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-gray-400">{{ $product->weight }} gr</p>
                            </div>
                            <a href="{{ route('produk.detail', $product->slug) }}" class="text-[11px] font-semibold text-[#3A4A3B] hover:underline transition">
                                + Keranjang
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-[#D6C7B2]/40">
                    <p class="text-gray-500 text-sm">Belum ada koleksi produk yang dipublikasikan.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- 3. KATEGORI PILIHAN (KOLEKSI SESUAI KEBUTUHANMU) -->
    <section id="kategori" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="text-center max-w-xl mx-auto mb-10">
            <p class="text-[10px] tracking-[0.2em] text-[#8C9B86] uppercase font-bold mb-1.5">KATEGORI PILIHAN</p>
            <h2 class="font-serif text-3xl text-[#24201D] mb-2">Koleksi Sesuai Kebutuhanmu</h2>
            <p class="text-xs text-gray-500">Didesain khusus untuk mempermudah gaya busana harian yang anggun dan santun.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Kategori 1: Pashmina -->
            <a href="{{ route('katalog.index', ['category' => 'pashmina']) }}" class="relative rounded-2xl overflow-hidden aspect-[3/4] group shadow-xs">
                <img src="https://images.unsplash.com/photo-1589998059171-989d887dda1e?auto=format&fit=crop&w=600&q=80" alt="Pashmina" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-[#24201D]/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-4 text-white">
                    <h3 class="font-serif text-lg">Pashmina</h3>
                    <p class="text-[10px] text-white/80">Silk, Ceruty & Rayon</p>
                </div>
            </a>

            <!-- Kategori 2: Segi Empat -->
            <a href="{{ route('katalog.index', ['category' => 'segi-empat']) }}" class="relative rounded-2xl overflow-hidden aspect-[3/4] group shadow-xs">
                <img src="https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?auto=format&fit=crop&w=600&q=80" alt="Segi Empat" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-[#24201D]/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-4 text-white">
                    <h3 class="font-serif text-lg">Segi Empat</h3>
                    <p class="text-[10px] text-white/80">Voal Ultrafine & Paris</p>
                </div>
            </a>

            <!-- Kategori 3: Instan -->
            <a href="{{ route('katalog.index', ['category' => 'instan']) }}" class="relative rounded-2xl overflow-hidden aspect-[3/4] group shadow-xs">
                <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=600&q=80" alt="Instan" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-[#24201D]/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-4 text-white">
                    <h3 class="font-serif text-lg">Instan</h3>
                    <p class="text-[10px] text-white/80">Daily Casual & Jersey</p>
                </div>
            </a>

            <!-- Kategori 4: Inner -->
            <a href="{{ route('katalog.index', ['category' => 'inner']) }}" class="relative rounded-2xl overflow-hidden aspect-[3/4] group shadow-xs">
                <img src="https://images.unsplash.com/photo-1583846783214-7229a91b20ed?auto=format&fit=crop&w=600&q=80" alt="Inner" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-[#24201D]/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-4 text-white">
                    <h3 class="font-serif text-lg">Inner</h3>
                    <p class="text-[10px] text-white/80">Anti Pusing & Breathable</p>
                </div>
            </a>
        </div>
    </section>

    <!-- 4. LAYANAN MANDIRI TAMU: LACAK PAKET -->
    <section id="lacak-paket" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-[#F3EFE6] border border-[#D6C7B2]/50 rounded-3xl p-8 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <p class="text-[10px] tracking-[0.18em] text-[#3A4A3B] uppercase font-bold mb-2">🚚 LAYANAN MANDIRI TAMU</p>
                <h2 class="font-serif text-2xl md:text-3xl text-[#24201D] mb-2">Lacak Paket</h2>
                <p class="text-xs text-gray-600 leading-relaxed max-w-md">
                    Pantau langsung status pengiriman pesananmu tanpa harus repot membuat akun pembeli.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-[#D6C7B2]/40 shadow-2xs">
                <form action="{{ route('order.track') }}" method="GET" class="space-y-3">
                    <label class="block text-[11px] font-semibold text-[#24201D]">
                        📦 Lacak No. Resi / Order ID
                    </label>
                    <input type="text" name="invoice" placeholder="Contoh: INV-202611... atau Resi JNE"
                           class="w-full text-xs border border-[#D6C7B2] rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#3A4A3B] bg-[#FAF7F2]/50">
                    <button type="submit" class="w-full bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold py-2.5 rounded-xl transition flex items-center justify-center gap-2">
                        <span>🔍</span>
                        <span>Lacak Paket Tamu</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- 5. COMMUNITY BANNER: TEMUKAN GAYA SEHARI-HARIMU -->
    <section id="perawatan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-16">
        <div class="bg-[#F7F4EC] border border-[#D6C7B2]/40 rounded-3xl py-12 px-6 text-center">
            <div class="w-9 h-9 rounded-full bg-white border border-[#D6C7B2]/50 text-[#3A4A3B] flex items-center justify-center mx-auto mb-4 text-sm shadow-2xs">
                ♡
            </div>
            <h2 class="font-serif text-2xl md:text-3xl text-[#24201D] mb-2">Temukan Gaya Sehari-harimu</h2>
            <p class="text-xs text-gray-500 max-w-md mx-auto mb-6 leading-relaxed">
                Tag OOTD modestmu dengan <span class="font-semibold text-[#3A4A3B]">#SheaDailyBabes</span> untuk kesempatan tampil di lookbook mingguan kami.
            </p>
            <a href="{{ route('katalog.index') }}" class="inline-block bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-[11px] font-semibold tracking-wider uppercase px-7 py-3 rounded-full transition shadow-xs">
                MULAI BELANJA SEKARANG
            </a>
        </div>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const prevBtn = document.getElementById('hero-prev');
    const nextBtn = document.getElementById('hero-next');

    if (slides.length <= 1) return;

    let currentIndex = 0;
    let slideInterval;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0', 'z-0', 'pointer-events-none');
                slide.classList.add('opacity-100', 'z-10');
            } else {
                slide.classList.remove('opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'z-0', 'pointer-events-none');
            }
        });

        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.remove('w-2', 'bg-white/50');
                dot.classList.add('w-6', 'bg-white');
            } else {
                dot.classList.remove('w-6', 'bg-white');
                dot.classList.add('w-2', 'bg-white/50');
            }
        });

        currentIndex = index;
    }

    function nextSlide() {
        showSlide((currentIndex + 1) % slides.length);
    }

    function prevSlide() {
        showSlide((currentIndex - 1 + slides.length) % slides.length);
    }

    function startAutoPlay() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    function resetAutoPlay() {
        clearInterval(slideInterval);
        startAutoPlay();
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetAutoPlay();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetAutoPlay();
        });
    }

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            const idx = parseInt(dot.getAttribute('data-slide'));
            showSlide(idx);
            resetAutoPlay();
        });
    });

    startAutoPlay();
});
</script>
@endsection