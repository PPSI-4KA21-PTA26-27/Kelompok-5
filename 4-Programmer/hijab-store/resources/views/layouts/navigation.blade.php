<nav x-data="{ open: false }" class="bg-[#FAF7F2]/95 backdrop-blur-md border-b border-[#D6C7B2]/40 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <!-- Kiri: Logo Brand -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <span class="w-9 h-9 rounded-lg bg-[#5A6D57] text-white font-serif text-sm flex items-center justify-center tracking-wider shadow-xs">
                        SD
                    </span>
                    <div class="leading-none">
                        <span class="font-serif text-lg font-bold tracking-wider text-[#24201D] block">SHEA DAILY</span>
                        <span class="text-[9px] tracking-[0.2em] text-[#8C9B86] uppercase font-semibold">MODEST & HIJAB</span>
                    </div>
                </a>
            </div>

            <!-- Tengah: Menu Utama Sejajar (Desktop) -->
            <div class="hidden md:flex items-center space-x-8 text-xs font-medium text-[#24201D]">
                <a href="{{ url('/') }}" 
                   class="{{ request()->is('/') ? 'border-b-2 border-[#5A6D57] text-[#5A6D57] font-semibold' : 'border-b-2 border-transparent hover:text-[#5A6D57]' }} pb-1 transition">
                    Home
                </a>
                <a href="{{ route('katalog.index') }}" 
                   class="{{ request()->routeIs('katalog.index') ? 'border-b-2 border-[#5A6D57] text-[#5A6D57] font-semibold' : 'border-b-2 border-transparent hover:text-[#5A6D57]' }} pb-1 transition">
                    Katalog
                </a>
                <a href="{{ route('bestseller.index') }}" 
                   class="{{ request()->routeIs('bestseller.index') ? 'border-b-2 border-[#5A6D57] text-[#5A6D57] font-semibold' : 'border-b-2 border-transparent hover:text-[#5A6D57]' }} pb-1 transition">
                    Best Seller
                </a>
                <a href="{{ route('fabric-care') }}" 
                   class="{{ request()->routeIs('fabric-care') ? 'border-b-2 border-[#5A6D57] text-[#5A6D57] font-semibold' : 'border-b-2 border-transparent hover:text-[#5A6D57]' }} pb-1 transition">
                    Perawatan Kain
                </a>
            </div>

            <!-- Kanan: Ikon Search, Keranjang & Tombol Login / Panel Admin -->
            <div class="hidden sm:flex items-center space-x-3">
                <!-- Tombol Pencarian ke Halaman Katalog -->
                <a href="{{ route('katalog.index') }}" title="Cari Produk" class="text-[#24201D] hover:text-[#5A6D57] transition p-2 rounded-full hover:bg-[#EFECE6]/60">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 10.5a7.5 7.5 0 0013.15 6.15z"/>
                    </svg>
                </a>

                <!-- Tombol Keranjang Belanja + Badge Jumlah Item -->
                @php
                    $cartCount = is_array(session('cart')) ? count(session('cart')) : 0;
                    $adminTargetRoute = Route::has('admin.dashboard') ? route('admin.dashboard') : route('admin.orders.index');
                @endphp
                <a href="{{ route('cart.index') }}" title="Keranjang Belanja" class="relative text-[#24201D] hover:text-[#5A6D57] transition p-2 rounded-full hover:bg-[#EFECE6]/60">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#5A6D57] text-white text-[9px] font-bold flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- Status Autentikasi (Login / Panel Admin / Keluar) -->
                <div class="pl-2 border-l border-[#D6C7B2]/50 flex items-center gap-2">
                    @auth
                        @if(in_array(Auth::user()->role, ['admin', 'pemilik', 'partner']))
                            <a href="{{ $adminTargetRoute }}" class="bg-[#5A6D57] hover:bg-[#495947] text-white text-[11px] font-semibold px-3.5 py-2 rounded-xl transition shadow-2xs">
                                Panel Admin
                            </a>
                        @else
                            <span class="text-[11px] font-medium text-[#24201D] px-2">
                                Halo, {{ Auth::user()->name }}
                            </span>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-[11px] font-medium text-gray-500 hover:text-red-600 px-2.5 py-1.5 rounded-lg hover:bg-red-50/60 transition">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-[#5A6D57] hover:bg-[#495947] text-white text-[11px] font-semibold px-4 py-2 rounded-xl transition shadow-2xs flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Masuk</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Hamburger Menu Mobile -->
            <div class="flex items-center sm:hidden gap-2">
                <a href="{{ route('katalog.index') }}" class="text-[#24201D] p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 10.5a7.5 7.5 0 0013.15 6.15z"/>
                    </svg>
                </a>
                <a href="{{ route('cart.index') }}" class="relative text-[#24201D] p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute top-0.5 right-0.5 w-3.5 h-3.5 rounded-full bg-[#5A6D57] text-white text-[8px] font-bold flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <button @click="open = ! open" class="p-2 rounded-md text-[#24201D] hover:bg-[#EFECE6] focus:outline-none">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu Mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#FAF7F2] border-t border-[#D6C7B2]/40 px-4 pt-3 pb-5 space-y-3 text-xs font-medium text-[#24201D]">
        <a href="{{ url('/') }}" class="block {{ request()->is('/') ? 'text-[#5A6D57] font-semibold' : 'hover:text-[#5A6D57]' }}">Home</a>
        <a href="{{ route('katalog.index') }}" class="block {{ request()->routeIs('katalog.index') ? 'text-[#5A6D57] font-semibold' : 'hover:text-[#5A6D57]' }}">Katalog</a>
        <a href="{{ route('bestseller.index') }}" class="block {{ request()->routeIs('bestseller.index') ? 'text-[#5A6D57] font-semibold' : 'hover:text-[#5A6D57]' }}">Best Seller</a>
        <a href="{{ route('fabric-care') }}" class="block {{ request()->routeIs('fabric-care') ? 'text-[#5A6D57] font-semibold' : 'hover:text-[#5A6D57]' }}">Perawatan Kain</a>
        <a href="{{ route('order.track') }}" class="block hover:text-[#5A6D57]">Lacak Pesanan</a>

        <div class="pt-3 border-t border-[#D6C7B2]/40">
            @auth
                <div class="flex items-center justify-between">
                    @if(in_array(Auth::user()->role, ['admin', 'pemilik', 'partner']))
                        <a href="{{ $adminTargetRoute }}" class="bg-[#5A6D57] text-white font-semibold px-3.5 py-2 rounded-xl">Panel Admin</a>
                    @else
                        <span class="text-[#24201D] font-semibold">{{ Auth::user()->name }}</span>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-500 font-semibold">Keluar</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="w-full bg-[#5A6D57] hover:bg-[#495947] text-white font-semibold py-2.5 rounded-xl flex items-center justify-center gap-2 transition">
                    <span>Masuk</span>
                </a>
            @endauth
        </div>
    </div>
</nav>