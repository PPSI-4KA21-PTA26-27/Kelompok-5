@extends('layouts.app')

@section('content')
<div class="bg-[#FAF7F2] text-[#24201D] min-h-screen py-8 md:py-12">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="flex items-center text-[10px] tracking-[0.18em] uppercase text-[#8C9B86] font-semibold mb-4 space-x-2">
            <a href="{{ url('/') }}" class="hover:text-[#3A4A3B] transition">BERANDA</a>
            <span>/</span>
            <span class="text-[#24201D]">KERANJANG BELANJA</span>
        </nav>

        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-3">
            <div>
                <p class="text-[10px] tracking-[0.2em] uppercase text-[#8C9B86] font-bold mb-1">SHEA ATELIER BAG</p>
                <h1 class="font-serif text-3xl text-[#24201D] font-normal">Keranjang Belanja</h1>
            </div>
            @if(!empty($cart) && count($cart) > 0)
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#3A4A3B]"></span>
                    <span><strong class="text-[#24201D]">{{ count($cart) }} item</strong> pilihan dalam tas belanja Anda</span>
                </div>
            @endif
        </div>

        @if(empty($cart) || count($cart) == 0)
            <!-- Tampilan Keranjang Kosong -->
            <div class="bg-white rounded-3xl p-12 text-center border border-[#D6C7B2]/40 my-8 max-w-2xl mx-auto shadow-2xs">
                <div class="w-14 h-14 bg-[#FAF7F2] border border-[#D6C7B2]/50 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
                    🛍️
                </div>
                <h3 class="font-serif text-xl text-[#24201D] mb-2">Tas Belanja Anda Masih Kosong</h3>
                <p class="text-xs text-gray-500 mb-8 max-w-sm mx-auto leading-relaxed">
                    Jelajahi koleksi hijab esensial Shea Daily dan temukan warna favorit untuk keseharian Anda.
                </p>
                <a href="{{ route('katalog.index') }}" class="inline-block bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold uppercase tracking-wider px-8 py-3.5 rounded-xl transition shadow-xs">
                    Jelajahi Koleksi
                </a>
            </div>
        @else
            <!-- Form Utama Keranjang ke Checkout -->
            <form action="{{ route('checkout.index') }}" method="GET" id="cart-form">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- KOLOM KIRI: KARTU DAFTAR ITEM & BADGE GARANSI (7 Kolom) -->
                    <div class="lg:col-span-7 space-y-6">

                        <!-- Box Putih Daftar Produk -->
                        <div class="bg-white rounded-3xl border border-[#D6C7B2]/40 p-6 md:p-8 shadow-[0_2px_15px_-4px_rgba(36,32,29,0.04)]">

                            <!-- Header Tabel -->
                            <div class="flex items-center justify-between pb-4 border-b border-[#F3EFE6] text-[10px] tracking-[0.15em] uppercase font-bold text-[#8C9B86]">
                                <label class="flex items-center gap-2.5 cursor-pointer text-[#24201D]">
                                    <input type="checkbox" id="select-all" checked class="w-3.5 h-3.5 rounded border-[#D6C7B2] text-[#3A4A3B] focus:ring-[#3A4A3B] cursor-pointer">
                                    <span>DETAIL BUSANA</span>
                                </label>
                                <div class="hidden sm:flex items-center gap-16 pr-6">
                                    <span>JUMLAH</span>
                                    <span>SUBTOTAL</span>
                                </div>
                            </div>

                            <!-- Loop Item Keranjang -->
                            <div class="divide-y divide-[#F3EFE6]">
                                @foreach($cart as $variantId => $item)
                                    @php
                                        $itemPrice = $item['price'] ?? 0;
                                        $itemQty = $item['quantity'] ?? 1;
                                        $subtotal = $itemPrice * $itemQty;
                                        $imagePath = !empty($item['image']) ? asset('storage/' . $item['image']) : null;
                                    @endphp

                                    <div class="py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 cart-item-row" data-variant-id="{{ $variantId }}" data-price="{{ $itemPrice }}">

                                        <!-- Kiri: Checkbox, Foto & Detail -->
                                        <div class="flex items-center gap-4">
                                            <input type="checkbox" name="selected_items[]" value="{{ $variantId }}" checked
                                                   class="item-checkbox w-3.5 h-3.5 rounded border-[#D6C7B2] text-[#3A4A3B] focus:ring-[#3A4A3B] cursor-pointer shrink-0">

                                            <!-- Foto Produk -->
                                            <div class="w-20 h-20 rounded-2xl overflow-hidden bg-[#EFECE6] border border-[#D6C7B2]/30 relative shrink-0">
                                                @if($loop->first)
                                                    <span class="absolute top-1.5 left-1.5 bg-[#3A4A3B] text-white text-[8px] font-bold uppercase px-1.5 py-0.5 rounded">Best Seller</span>
                                                @endif
                                                @if($imagePath)
                                                    <img src="{{ $imagePath }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-[10px]">No Pic</div>
                                                @endif
                                            </div>

                                            <!-- Nama, Varian & Harga Satuan -->
                                            <div class="space-y-1">
                                                <h3 class="font-serif text-sm md:text-base text-[#24201D] font-normal leading-snug">
                                                    <a href="{{ isset($item['slug']) ? route('produk.detail', $item['slug']) : '#' }}" class="hover:text-[#3A4A3B] transition">
                                                        {{ $item['name'] }}
                                                    </a>
                                                </h3>
                                                <p class="text-[11px] text-gray-500">
                                                    Varian: <span class="font-semibold text-[#24201D] capitalize">{{ $item['color'] ?? '-' }}</span>
                                                    @if(!empty($item['size']))
                                                        <span class="text-gray-400"> • {{ $item['size'] }}</span>
                                                    @endif
                                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#8C9B86] ml-1 align-middle"></span>
                                                </p>
                                                <p class="text-[10px] text-gray-400">Premium Modest Series</p>
                                                <p class="text-xs font-medium text-[#24201D] pt-0.5">Rp {{ number_format($itemPrice, 0, ',', '.') }}</p>
                                            </div>
                                        </div>

                                        <!-- Kanan: Kontrol Kuantitas, Subtotal & Hapus -->
                                        <div class="flex items-center justify-between sm:justify-end gap-6 pl-7 sm:pl-0">
                                            <!-- Stepper Jumlah -->
                                            <div class="flex items-center bg-[#F3EFE6] border border-[#D6C7B2]/50 rounded-lg h-8 px-1">
                                                <button type="button" class="btn-qty-minus w-6 h-6 text-[#24201D] hover:bg-white rounded transition flex items-center justify-center text-xs font-semibold">−</button>
                                                <input type="number" name="quantities[{{ $variantId }}]" value="{{ $itemQty }}" min="1"
                                                       class="item-qty-input w-8 text-center border-none text-xs focus:ring-0 p-0 font-semibold bg-transparent text-[#24201D]" readonly>
                                                <button type="button" class="btn-qty-plus w-6 h-6 text-[#24201D] hover:bg-white rounded transition flex items-center justify-center text-xs font-semibold">+</button>
                                            </div>

                                            <!-- Subtotal & Tombol Tong Sampah -->
                                            <div class="flex items-center gap-3">
                                                <span class="font-serif text-sm font-semibold text-[#24201D] item-subtotal-display min-w-[85px] text-right">
                                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                                </span>

                                                <button type="button" class="btn-remove-item text-gray-400 hover:text-red-500 transition p-1" title="Hapus item" data-variant-id="{{ $variantId }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tautan Lanjut Belanja -->
                        <div>
                            <a href="{{ route('katalog.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-[#24201D] hover:text-[#3A4A3B] border-b border-[#24201D] hover:border-[#3A4A3B] pb-1 transition">
                                <span>←</span>
                                <span>Lanjut Belanja Koleksi Lain</span>
                            </a>
                        </div>

                        <!-- 3 Kartu Jaminan Kualitas di Bawah -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                            <div class="bg-[#F3EFE6]/80 border border-[#D6C7B2]/40 rounded-2xl p-4 space-y-1.5">
                                <div class="text-[#3A4A3B] text-sm">🛡️</div>
                                <h4 class="text-xs font-semibold text-[#24201D]">100% Serat Pilihan</h4>
                                <p class="text-[10px] text-gray-500 leading-relaxed">Bahan alami bernapas, sejuk di kulit sensitif sepanjang hari.</p>
                            </div>

                            <div class="bg-[#F3EFE6]/80 border border-[#D6C7B2]/40 rounded-2xl p-4 space-y-1.5">
                                <div class="text-[#3A4A3B] text-sm">📦</div>
                                <h4 class="text-xs font-semibold text-[#24201D]">Eco-Conscious Wrap</h4>
                                <p class="text-[10px] text-gray-500 leading-relaxed">Dikemas rapi dengan kotak daur ulang bebas plastik sekali pakai.</p>
                            </div>

                            <div class="bg-[#F3EFE6]/80 border border-[#D6C7B2]/40 rounded-2xl p-4 space-y-1.5">
                                <div class="text-[#3A4A3B] text-sm">🔄</div>
                                <h4 class="text-xs font-semibold text-[#24201D]">Garansi Kesesuaian</h4>
                                <p class="text-[10px] text-gray-500 leading-relaxed">Penukaran warna atau ukuran mudah dalam 7 hari penerimaan.</p>
                            </div>
                        </div>

                    </div>

                    <!-- KOLOM KANAN: RINGKASAN BELANJA (5 Kolom) -->
                    <div class="lg:col-span-5">
                        <div class="bg-white rounded-3xl p-6 md:p-8 border border-[#D6C7B2]/40 space-y-6 sticky top-28 shadow-[0_2px_15px_-4px_rgba(36,32,29,0.04)]">

                            <div>
                                <h2 class="font-serif text-xl text-[#24201D] font-normal">Ringkasan Belanja</h2>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">Periksa kembali pesanan sebelum melanjutkan transaksi aman.</p>
                            </div>

                            <div class="space-y-3.5 text-xs pt-2">
                                <div class="flex justify-between items-center text-gray-600">
                                    <span>Total Harga (<span id="selected-count-display" class="font-medium text-[#24201D]">0 produk</span>)</span>
                                    <span id="total-price-display" class="font-semibold text-[#24201D]">Rp 0</span>
                                </div>

                                <div class="flex justify-between items-center text-gray-600">
                                    <span class="flex items-center gap-1">
                                        Estimasi Ongkos Kirim
                                        <span class="text-[10px] text-gray-400" title="Tarif otomatis via RajaOngkir">ⓘ</span>
                                    </span>
                                    <span class="text-gray-400">Dihitung saat checkout</span>
                                </div>

                                <div class="flex justify-between items-center text-gray-600">
                                    <span>Biaya Penanganan Atelier</span>
                                    <span class="font-semibold text-[#3A4A3B]">GRATIS</span>
                                </div>
                            </div>

                            <hr class="border-[#F3EFE6]">

                            <!-- Total Akhir -->
                            <div>
                                <div class="flex justify-between items-baseline">
                                    <span class="font-serif text-base font-semibold text-[#24201D]">Total Akhir</span>
                                    <span id="grand-total-display" class="font-serif text-xl font-bold text-[#24201D]">Rp 0</span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Termasuk PPN & garansi packaging aman</p>
                            </div>

                            <!-- Tombol Lanjut ke Pembayaran -->
                            <button type="submit" id="btn-checkout"
                                    class="w-full bg-[#3A4A3B] hover:bg-[#2C392D] disabled:bg-gray-300 disabled:cursor-not-allowed text-white py-3.5 rounded-xl text-xs uppercase font-semibold tracking-wider transition duration-200 shadow-xs flex items-center justify-center gap-2">
                                <span>LANJUT KE PEMBAYARAN</span>
                                <span>→</span>
                            </button>

                            <!-- Footer Keamanan -->
                            <div class="text-center space-y-2 pt-1">
                                <div class="flex items-center justify-center gap-1.5 text-[10px] text-gray-500 font-medium">
                                    <span>🔒</span>
                                    <span>Checkout Enkripsi 256-bit • Pembayaran Aman Terverifikasi</span>
                                </div>
                                <p class="text-[10px] text-gray-400 leading-relaxed max-w-xs mx-auto">
                                    Shea Daily menjamin setiap helai hijab dan busana modest melewati tahap kurasi kontrol kualitas presisi sebelum pengiriman.
                                </p>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        @endif
    </section>
</div>

<!-- Form Tersembunyi untuk Hapus Item -->
<form id="delete-item-form" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const btnCheckout = document.getElementById('btn-checkout');
        const totalPriceDisplay = document.getElementById('total-price-display');
        const grandTotalDisplay = document.getElementById('grand-total-display');
        const selectedCountDisplay = document.getElementById('selected-count-display');

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function calculateSummary() {
            let total = 0;
            let count = 0;

            itemCheckboxes.forEach(cb => {
                if (cb.checked) {
                    const row = cb.closest('.cart-item-row');
                    const price = parseFloat(row.getAttribute('data-price')) || 0;
                    const qtyInput = row.querySelector('.item-qty-input');
                    const qty = parseInt(qtyInput.value) || 1;

                    total += price * qty;
                    count += 1;
                }
            });

            if (totalPriceDisplay) totalPriceDisplay.textContent = formatRupiah(total);
            if (grandTotalDisplay) grandTotalDisplay.textContent = formatRupiah(total);
            if (selectedCountDisplay) selectedCountDisplay.textContent = `${count} Produk`;

            if (btnCheckout) {
                btnCheckout.disabled = count === 0;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                itemCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                calculateSummary();
            });
        }

        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (!this.checked && selectAll) {
                    selectAll.checked = false;
                } else if (selectAll) {
                    const allChecked = Array.from(itemCheckboxes).every(c => c.checked);
                    selectAll.checked = allChecked;
                }
                calculateSummary();
            });
        });

        document.querySelectorAll('.cart-item-row').forEach(row => {
            const btnMinus = row.querySelector('.btn-qty-minus');
            const btnPlus = row.querySelector('.btn-qty-plus');
            const qtyInput = row.querySelector('.item-qty-input');
            const subtotalDisplay = row.querySelector('.item-subtotal-display');
            const price = parseFloat(row.getAttribute('data-price')) || 0;

            function updateRowSubtotal() {
                const qty = parseInt(qtyInput.value) || 1;
                const subtotal = price * qty;
                if (subtotalDisplay) subtotalDisplay.textContent = formatRupiah(subtotal);
                calculateSummary();
            }

            if (btnMinus && qtyInput) {
                btnMinus.addEventListener('click', function () {
                    let currentVal = parseInt(qtyInput.value) || 1;
                    if (currentVal > 1) {
                        qtyInput.value = currentVal - 1;
                        updateRowSubtotal();
                    }
                });
            }

            if (btnPlus && qtyInput) {
                btnPlus.addEventListener('click', function () {
                    let currentVal = parseInt(qtyInput.value) || 1;
                    qtyInput.value = currentVal + 1;
                    updateRowSubtotal();
                });
            }
        });

        const deleteForm = document.getElementById('delete-item-form');
        document.querySelectorAll('.btn-remove-item').forEach(btn => {
            btn.addEventListener('click', function () {
                const variantId = this.getAttribute('data-variant-id');
                if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                    deleteForm.action = `/keranjang/hapus/${variantId}`;
                    deleteForm.submit();
                }
            });
        });

        calculateSummary();
    });
</script>
@endsection