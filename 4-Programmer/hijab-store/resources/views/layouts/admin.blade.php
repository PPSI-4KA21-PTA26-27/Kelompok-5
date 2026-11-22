<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Shea Daily Management Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600,700|plus-jakarta-sans:400,500,600,700|jetbrains-mono:400,500&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles (Alpine sudah dimuat melalui app.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif { font-family: 'Cormorant Garamond', serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-[#FAF7F2] text-[#24201D] antialiased" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- Overlay Mobile saat Sidebar terbuka -->
        <div x-show="sidebarOpen"
             x-transition.opacity
             @click="sidebarOpen = false"
             style="display: none;"
             class="fixed inset-0 z-30 bg-black/30 backdrop-blur-2xs lg:hidden"></div>

        <!-- SIDEBAR KIRI (Fixed Drawer di Mobile, Sticky Full-Height di Desktop) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-[#D6C7B2]/50 flex flex-col justify-between transition-transform duration-200 ease-in-out lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen shrink-0 overflow-y-auto">

            <div>
                <!-- Header Logo Brand Portal -->
                <div class="px-6 py-6 border-b border-[#D6C7B2]/40 flex items-center justify-between">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group" title="Lihat Toko Pembeli">
                        <span class="w-9 h-9 rounded-full bg-[#3A4A3B] text-white font-serif text-sm flex items-center justify-center tracking-wider shadow-2xs shrink-0">
                            SD
                        </span>
                        <div class="leading-tight">
                            <span class="font-serif text-base font-bold tracking-wider text-[#24201D] block">SHEA DAILY</span>
                            <span class="text-[9px] tracking-[0.18em] text-[#8C9B86] uppercase font-semibold">MANAGEMENT PORTAL</span>
                        </div>
                    </a>

                    <!-- Tombol Tutup di Mobile -->
                    <button type="button" @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-[#24201D] hover:bg-[#F3EFE6]">
                        ✕
                    </button>
                </div>

                <!-- Menu Navigasi Utama -->
                <nav class="px-4 py-5 space-y-1 text-xs font-medium">

                    <!-- 1. Dashboard -->
                    @php
                        $isDashboard = request()->routeIs('admin.dashboard');
                    @endphp
                    <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}"
                       class="{{ $isDashboard ? 'bg-[#3A4A3B] text-white font-semibold shadow-2xs' : 'text-[#24201D] hover:bg-[#F3EFE6]' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl transition">
                        <span class="flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Dashboard</span>
                        </span>
                        @if($isDashboard)
                            <span class="w-1.5 h-1.5 rounded-full bg-[#A7F3D0]"></span>
                        @endif
                    </a>

                    <!-- 2. Produk -->
                    @php
                        $isProducts = request()->routeIs('admin.products.*');
                    @endphp
                    <a href="{{ route('admin.products.index') }}"
                       class="{{ $isProducts ? 'bg-[#3A4A3B] text-white font-semibold shadow-2xs' : 'text-[#24201D] hover:bg-[#F3EFE6]' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl transition">
                        <span class="flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Produk</span>
                        </span>
                        @if($isProducts)
                            <span class="w-1.5 h-1.5 rounded-full bg-[#A7F3D0]"></span>
                        @endif
                    </a>

                    <!-- 3. Kategori Produk -->
                    @if(Route::has('admin.categories.index'))
                        @php
                            $isCategories = request()->routeIs('admin.categories.*');
                        @endphp
                        <a href="{{ route('admin.categories.index') }}"
                           class="{{ $isCategories ? 'bg-[#3A4A3B] text-white font-semibold shadow-2xs' : 'text-[#24201D] hover:bg-[#F3EFE6]' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl transition">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Kategori Produk</span>
                            </span>
                            @if($isCategories)
                                <span class="w-1.5 h-1.5 rounded-full bg-[#A7F3D0]"></span>
                            @endif
                        </a>
                    @endif

                    <!-- 4. Stok per Varian -->
                    @php
                        $isStocks = request()->routeIs('admin.stocks.*');
                    @endphp
                    <a href="{{ Route::has('admin.stocks.index') ? route('admin.stocks.index') : '#' }}"
                       class="{{ $isStocks ? 'bg-[#3A4A3B] text-white font-semibold shadow-2xs' : 'text-[#24201D] hover:bg-[#F3EFE6]' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl transition">
                        <span class="flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span>Stok per Varian</span>
                        </span>
                        @if($isStocks)
                            <span class="w-1.5 h-1.5 rounded-full bg-[#A7F3D0]"></span>
                        @endif
                    </a>

                    <!-- 5. Pesanan -->
                    @php
                        $isOrders = request()->routeIs('admin.orders.*');
                    @endphp
                    <a href="{{ route('admin.orders.index') }}"
                       class="{{ $isOrders ? 'bg-[#3A4A3B] text-white font-semibold shadow-2xs' : 'text-[#24201D] hover:bg-[#F3EFE6]' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl transition">
                        <span class="flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Pesanan</span>
                        </span>
                        @if($isOrders)
                            <span class="w-1.5 h-1.5 rounded-full bg-[#A7F3D0]"></span>
                        @endif
                    </a>

                    <!-- 6. Konten Halaman -->
                    @php
                        $isContent = request()->routeIs('admin.banners.*') || request()->routeIs('admin.pages.*');
                    @endphp
                    <a href="{{ route('admin.banners.index') }}"
                       class="{{ $isContent ? 'bg-[#3A4A3B] text-white font-semibold shadow-2xs' : 'text-[#24201D] hover:bg-[#F3EFE6]' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl transition">
                        <span class="flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Konten Halaman</span>
                        </span>
                        @if($isContent)
                            <span class="w-1.5 h-1.5 rounded-full bg-[#A7F3D0]"></span>
                        @endif
                    </a>

                    <!-- 7. Rekap Penjualan (*hanya Pemilik) -->
                    @if(in_array(Auth::user()->role ?? 'pemilik', ['pemilik', 'admin']))
                        @php
                            $isReports = request()->routeIs('admin.reports.*');
                        @endphp
                        <a href="{{ Route::has('admin.reports.index') ? route('admin.reports.index') : '#' }}"
                           class="{{ $isReports ? 'bg-[#3A4A3B] text-white font-semibold shadow-2xs' : 'text-[#24201D] hover:bg-[#F3EFE6]' }} flex items-center justify-between px-3.5 py-2.5 rounded-xl transition">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span>Rekap Penjualan</span>
                            </span>
                            <span class="text-[10px] opacity-70">*</span>
                        </a>
                    @endif

                </nav>
            </div>

            <!-- Footer Sidebar: Profil Aktif, Lihat Etalase & Keluar -->
            <div class="p-4 border-t border-[#D6C7B2]/40 space-y-2">
                @auth
                    <div class="px-3.5 py-2 rounded-xl bg-[#FAF7F2] border border-[#D6C7B2]/40">
                        <p class="text-[11px] font-semibold text-[#24201D] truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-[#8C9B86] uppercase font-semibold tracking-wider">{{ Auth::user()->role }}</p>
                    </div>
                @endauth

                <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-medium text-[#3A4A3B] hover:bg-[#F3EFE6] transition">
                    <span>↗ Lihat Etalase Toko</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-red-500 hover:bg-red-50 transition text-left">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>

                <p class="px-3.5 text-[10px] italic text-gray-400">*hanya Pemilik</p>
            </div>
        </aside>

        <!-- AREA KONTEN UTAMA SEBELAH KANAN -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top Bar Mobile (Hanya tampil di layar < 1024px) -->
            <header class="bg-white border-b border-[#D6C7B2]/40 px-4 py-3 flex items-center justify-between lg:hidden sticky top-0 z-20">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-[#3A4A3B] text-white font-serif text-xs flex items-center justify-center">SD</span>
                    <span class="font-serif font-bold text-sm text-[#24201D]">SHEA DAILY ADMIN</span>
                </div>
                <button type="button" @click="sidebarOpen = true" class="p-2 rounded-lg text-[#24201D] hover:bg-[#F3EFE6]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </header>

            <!-- Konten Halaman -->
            <main class="flex-1">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>