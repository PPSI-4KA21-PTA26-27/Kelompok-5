<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Shea Daily') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Scripts & Styles (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#FAF7F2] min-h-screen flex flex-col text-[#24201D]">

        <!-- Navigasi Utama -->
        @include('layouts.navigation')

        <main class="flex-grow">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>

        <!-- Footer 3 Kolom -->
        <footer class="bg-[#F3EFE6] border-t border-[#D6C7B2]/50 pt-12 pb-8 mt-auto text-[#24201D]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-10 border-b border-[#D6C7B2]/50">

                    <!-- Kolom 1: Brand Info & Sosmed -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-md bg-[#3A4A3B] text-white font-serif text-xs flex items-center justify-center">SD</span>
                            <h3 class="font-serif text-lg font-bold tracking-wider text-[#24201D]">SHEA DAILY</h3>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed max-w-sm">
                            Kurasi busana modest kontemporer dan hijab pilihan dengan kenyamanan bahan, keanggunan bersahaja, serta jahitan rapi untuk aktivitas sehari-hari.
                        </p>
                        <div class="flex items-center gap-2.5 pt-1">
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                               class="w-8 h-8 rounded-full bg-white border border-[#D6C7B2]/60 flex items-center justify-center text-[11px] font-semibold hover:bg-[#3A4A3B] hover:text-white transition">
                                IG
                            </a>
                            <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer"
                               class="w-8 h-8 rounded-full bg-white border border-[#D6C7B2]/60 flex items-center justify-center text-[11px] font-semibold hover:bg-[#3A4A3B] hover:text-white transition">
                                TT
                            </a>
                            <a href="https://wa.me/" target="_blank" rel="noopener noreferrer"
                               class="w-8 h-8 rounded-full bg-white border border-[#D6C7B2]/60 flex items-center justify-center text-[11px] font-semibold hover:bg-[#3A4A3B] hover:text-white transition">
                                WA
                            </a>
                        </div>
                    </div>

                    <!-- Kolom 2: Koleksi & Kategori -->
                    <div class="space-y-3">
                        <h4 class="text-[11px] font-bold uppercase tracking-widest text-[#24201D]">KOLEKSI</h4>
                        <ul class="space-y-2 text-xs text-gray-600">
                            <li><a href="{{ route('katalog.index') }}" class="hover:text-[#3A4A3B] transition">Semua Koleksi</a></li>
                            <li><a href="{{ route('katalog.index', ['category' => 'pashmina-silk']) }}" class="hover:text-[#3A4A3B] transition">Pashmina Silk</a></li>
                            <li><a href="{{ route('katalog.index', ['category' => 'segi-empat-voal']) }}" class="hover:text-[#3A4A3B] transition">Segi Empat Voal</a></li>
                            <li><a href="{{ route('katalog.index', ['category' => 'instant-hijab']) }}" class="hover:text-[#3A4A3B] transition">Instant Hijab</a></li>
                            <li><a href="{{ route('katalog.index', ['category' => 'modest-wear']) }}" class="hover:text-[#3A4A3B] transition">Modest Wear</a></li>
                        </ul>
                    </div>

                    <!-- Kolom 3: Bantuan & Informasi -->
                    <div class="space-y-3">
                        <h4 class="text-[11px] font-bold uppercase tracking-widest text-[#24201D]">LAYANAN</h4>
                        <ul class="space-y-2 text-xs text-gray-600">
                            <li><a href="{{ route('order.track') }}" class="hover:text-[#3A4A3B] transition font-semibold text-[#3A4A3B]">Lacak Pesanan</a></li>
                            <li><a href="{{ route('cart.index') }}" class="hover:text-[#3A4A3B] transition">Tas Belanja</a></li>
                            <li><a href="{{ url('/best-seller') }}" class="hover:text-[#3A4A3B] transition">Produk Terlaris</a></li>
                            <li><a href="{{ url('/perawatan-kain') }}" class="hover:text-[#3A4A3B] transition">Perawatan Kain</a></li>
                        </ul>
                    </div>

                </div>

                <!-- Copyright Bawah -->
                <div class="pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-[11px] text-gray-500">
                    <p>&copy; {{ date('Y') }} Shea Daily. All Rights Reserved.</p>
                    <p>Modest Wear &amp; Hijab Everyday</p>
                </div>

            </div>
        </footer>
    </body>
</html>