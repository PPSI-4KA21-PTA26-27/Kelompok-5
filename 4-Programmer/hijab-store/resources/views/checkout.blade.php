@extends('layouts.app')

@section('content')
<div class="bg-[#FAF7F2] text-[#24201D] min-h-screen py-8 md:py-12">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Menampilkan Error Validasi Form --}}
        @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-2xl mb-6 text-xs border border-red-200">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Menampilkan Error Sistem/Midtrans --}}
        @if(session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-2xl mb-6 text-xs border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <!-- Breadcrumb -->
        <nav class="flex items-center text-[10px] tracking-[0.18em] uppercase text-[#8C9B86] font-semibold mb-4 space-x-2">
            <a href="{{ url('/') }}" class="hover:text-[#3A4A3B] transition">KOLEKSI SHEA</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-[#3A4A3B] transition">TAS BELANJA</a>
            <span>/</span>
            <span class="text-[#24201D]">GUEST CHECKOUT</span>
        </nav>

        <!-- Header Halaman + Badge Keamanan -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl text-[#24201D] font-normal tracking-tight">Pemesanan Tamu & Layanan Cepat</h1>
                <p class="text-xs text-gray-500 mt-1.5">Selesaikan pembelian langsung tanpa registrasi akun ataupun kata sandi.</p>
            </div>

            <div class="inline-flex items-center gap-3 bg-[#F3EFE6] border border-[#D6C7B2]/60 px-4 py-2 rounded-full text-[11px] font-medium text-[#24201D] self-start md:self-auto">
                <span class="flex items-center gap-1.5">
                    <span>🔒</span> Enkripsi 256-bit
                </span>
                <span class="text-[#D6C7B2]">•</span>
                <span class="flex items-center gap-1.5 text-[#3A4A3B] font-semibold">
                    <span>🛡️</span> Midtrans Verified
                </span>
            </div>
        </div>

        @if(empty($cartItems) || (is_countable($cartItems) && count($cartItems) == 0))
            <div class="bg-white rounded-3xl p-12 text-center border border-[#D6C7B2]/40 max-w-xl mx-auto shadow-2xs">
                <h3 class="font-serif text-xl text-[#24201D] mb-2">Tidak Ada Produk Untuk Di-checkout</h3>
                <p class="text-xs text-gray-500 mb-6">Silakan pilih minimal satu produk dari tas belanja Anda terlebih dahulu.</p>
                <a href="{{ route('cart.index') }}" class="inline-block bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold uppercase tracking-wider px-8 py-3.5 rounded-xl transition">
                    Kembali ke Tas Belanja
                </a>
            </div>
        @else
            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- KOLOM KIRI: STEP 1 INFORMASI PENGIRIMAN & RAJAONGKIR (7 Kolom) -->
                    <div class="lg:col-span-7">
                        <div class="bg-white rounded-3xl p-6 md:p-8 border border-[#D6C7B2]/40 shadow-[0_2px_15px_-4px_rgba(36,32,29,0.04)] space-y-6">

                            <!-- Header Step 1 -->
                            <div class="flex items-center justify-between pb-2">
                                <div class="flex items-center gap-3">
                                    <span class="w-7 h-7 rounded-full bg-[#3A4A3B] text-white text-xs font-bold flex items-center justify-center">1</span>
                                    <h2 class="font-serif text-lg md:text-xl text-[#24201D]">Informasi Pengiriman Instan</h2>
                                </div>
                                <span class="bg-[#F3EFE6] border border-[#D6C7B2]/50 text-[#3A4A3B] text-[10px] font-semibold px-3 py-1 rounded-full">
                                    Tanpa Kata Sandi
                                </span>
                            </div>

                            <!-- Nama Penerima & No. WhatsApp (2 Kolom) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="customer_name" class="block text-[11px] font-medium text-[#24201D] mb-1.5">Nama Lengkap Penerima *</label>
                                    <input type="text" name="customer_name" id="customer_name" required
                                           value="{{ old('customer_name', $user->name ?? '') }}"
                                           placeholder="Contoh: Sarah Az-Zahra"
                                           class="w-full text-xs bg-[#FAF7F2]/50 border border-[#D6C7B2] rounded-xl px-4 py-3 text-[#24201D] focus:bg-white focus:ring-[#3A4A3B] focus:border-[#3A4A3B] transition">
                                    @error('customer_name')
                                        <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_phone" class="block text-[11px] font-medium text-[#24201D] mb-1.5">No. WhatsApp (Notifikasi Resi Otomatis) *</label>
                                    <input type="tel" name="customer_phone" id="customer_phone" required
                                           value="{{ old('customer_phone', $user->phone ?? '') }}"
                                           placeholder="+62 812-3456-7890"
                                           class="w-full text-xs bg-[#FAF7F2]/50 border border-[#D6C7B2] rounded-xl px-4 py-3 text-[#24201D] focus:bg-white focus:ring-[#3A4A3B] focus:border-[#3A4A3B] transition">
                                    @error('customer_phone')
                                        <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Alamat Jalan -->
                            <div>
                                <label for="shipping_address" class="block text-[11px] font-medium text-[#24201D] mb-1.5">Alamat Jalan & No. Rumah / Gedung *</label>
                                <textarea name="shipping_address" id="shipping_address" rows="3" required
                                          placeholder="Nama Jalan, Nomor Rumah, RT/RW, Kecamatan, Kode Pos"
                                          class="w-full text-xs bg-[#FAF7F2]/50 border border-[#D6C7B2] rounded-xl px-4 py-3 text-[#24201D] focus:bg-white focus:ring-[#3A4A3B] focus:border-[#3A4A3B] transition">{{ old('shipping_address', $user->address ?? '') }}</textarea>
                                @error('shipping_address')
                                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Box Integrasi Lokasi RajaOngkir -->
                            <div class="bg-[#F3EFE6]/80 border border-[#D6C7B2]/60 rounded-2xl p-5 space-y-4">
                                <div class="flex items-center gap-2 text-xs font-semibold text-[#24201D]">
                                    <span>🚚</span>
                                    <span>Integrasi Lokasi RajaOngkir</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="province_id" class="block text-[11px] text-gray-600 mb-1.5">Provinsi *</label>
                                        <select id="province_id" name="province_id" required
                                                class="w-full text-xs bg-white border border-[#D6C7B2] rounded-xl px-3.5 py-2.5 text-[#24201D] focus:ring-[#3A4A3B] focus:border-[#3A4A3B] transition">
                                            <option value="">-- Pilih Provinsi --</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province['province_id'] }}">{{ $province['province'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="city_id" class="block text-[11px] text-gray-600 mb-1.5">Kota / Kabupaten *</label>
                                        <select id="city_id" name="city_id" required disabled
                                                class="w-full text-xs bg-white border border-[#D6C7B2] rounded-xl px-3.5 py-2.5 text-[#24201D] focus:ring-[#3A4A3B] focus:border-[#3A4A3B] transition disabled:bg-[#FAF7F2] disabled:text-gray-400 disabled:cursor-not-allowed">
                                            <option value="">-- Pilih Provinsi Dahulu --</option>
                                        </select>
                                        @error('city_id')
                                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Pilih Opsi Kurir Real-time -->
                            <div class="space-y-3">
                                <label class="block text-xs font-semibold text-[#24201D]">Pilih Opsi Kurir Real-time *</label>

                                <select name="courier" id="courier" required class="hidden">
                                    <option value="">Pilih Kurir</option>
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS</option>
                                    <option value="tiki">TIKI</option>
                                </select>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3" id="courier-cards-container">
                                    <button type="button" data-courier="jne" disabled
                                            class="courier-card text-left p-4 rounded-2xl border border-[#D6C7B2]/60 bg-[#F3EFE6]/60 hover:border-[#3A4A3B] transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="font-bold text-sm text-[#24201D]">JNE</span>
                                            <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded bg-[#D6C7B2]/50 text-[#24201D]">REG / YES</span>
                                        </div>
                                        <p class="text-[11px] text-gray-500">2-3 Hari Pengiriman</p>
                                        <p class="text-xs font-semibold text-[#3A4A3B] mt-2">Cek Tarif →</p>
                                    </button>

                                    <button type="button" data-courier="pos" disabled
                                            class="courier-card text-left p-4 rounded-2xl border border-[#D6C7B2]/60 bg-[#F3EFE6]/60 hover:border-[#3A4A3B] transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="font-bold text-sm text-[#24201D]">POS</span>
                                            <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded bg-[#8C9B86] text-white">HEMAT</span>
                                        </div>
                                        <p class="text-[11px] text-gray-500">2-4 Hari Pengiriman</p>
                                        <p class="text-xs font-semibold text-[#3A4A3B] mt-2">Cek Tarif →</p>
                                    </button>

                                    <button type="button" data-courier="tiki" disabled
                                            class="courier-card text-left p-4 rounded-2xl border border-[#D6C7B2]/60 bg-[#F3EFE6]/60 hover:border-[#3A4A3B] transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="font-bold text-sm text-[#24201D]">TIKI</span>
                                            <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded bg-[#D6C7B2]/50 text-[#24201D]">ONS / REG</span>
                                        </div>
                                        <p class="text-[11px] text-gray-500">2 Hari Pengiriman</p>
                                        <p class="text-xs font-semibold text-[#3A4A3B] mt-2">Cek Tarif →</p>
                                    </button>
                                </div>
                                @error('courier')
                                    <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror

                                <div class="pt-1">
                                    <label for="shipping_service" class="block text-[11px] text-gray-600 mb-1.5">Layanan Paket Pengiriman *</label>
                                    <select id="shipping_service" name="shipping_service" required disabled
                                            class="w-full text-xs bg-[#FAF7F2]/50 border border-[#D6C7B2] rounded-xl px-4 py-3 text-[#24201D] focus:bg-white focus:ring-[#3A4A3B] focus:border-[#3A4A3B] transition disabled:opacity-60 disabled:cursor-not-allowed">
                                        <option value="">-- Pilih Kota & Kurir Terlebih Dahulu --</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Catatan Pengiriman -->
                            <div>
                                <label for="notes" class="block text-[11px] font-medium text-[#24201D] mb-1.5">Catatan Pengiriman (Opsional)</label>
                                <input type="text" name="notes" id="notes"
                                       placeholder="Contoh: Titipkan ke satpam jika sedang tidak di rumah"
                                       class="w-full text-xs bg-[#FAF7F2]/50 border border-[#D6C7B2] rounded-xl px-4 py-3 text-[#24201D] focus:bg-white focus:ring-[#3A4A3B] focus:border-[#3A4A3B] transition">
                            </div>

                        </div>
                    </div>

                    <!-- KOLOM KANAN: STEP 2 RINGKASAN PEMBELIAN (5 Kolom) -->
                    <div class="lg:col-span-5">
                        <div class="bg-white rounded-3xl p-6 md:p-8 border border-[#D6C7B2]/40 space-y-6 sticky top-28 shadow-[0_2px_15px_-4px_rgba(36,32,29,0.04)]">

                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-[#3A4A3B] text-white text-xs font-bold flex items-center justify-center">2</span>
                                <h2 class="font-serif text-lg md:text-xl text-[#24201D]">Ringkasan Pembelian</h2>
                            </div>

                            <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                                @php
                                    $totalBelanjaCalculated = 0;
                                    $totalItemCount = 0;
                                @endphp

                                @foreach($cartItems as $item)
                                    @php
                                        $isObject = is_object($item);
                                        $name = $isObject ? $item->variant->product->name : $item['name'];
                                        $color = $isObject ? $item->variant->color : $item['color'];
                                        $size = $isObject ? ($item->variant->size ?? null) : ($item['size'] ?? null);
                                        $price = $isObject ? $item->variant->product->price : $item['price'];
                                        $quantity = $isObject ? $item->quantity : $item['quantity'];
                                        $image = $isObject ? ($item->variant->image ?: $item->variant->product->image) : $item['image'];
                                        $variantId = $isObject ? $item->product_variant_id : ($item['variant_id'] ?? $loop->index);

                                        $itemSubtotal = $price * $quantity;
                                        $totalBelanjaCalculated += $itemSubtotal;
                                        $totalItemCount += $quantity;
                                        $imagePath = !empty($image) ? asset('storage/' . $image) : null;
                                    @endphp

                                    <input type="hidden" name="selected_items[]" value="{{ $variantId }}">

                                    <div class="bg-[#F3EFE6]/70 border border-[#D6C7B2]/40 rounded-2xl p-3.5 flex items-center gap-3.5">
                                        <div class="w-14 h-16 shrink-0 rounded-xl overflow-hidden bg-white border border-[#D6C7B2]/30">
                                            @if($imagePath)
                                                <img src="{{ $imagePath }}" alt="{{ $name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-[10px]">No Img</div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-serif text-sm text-[#24201D] truncate">{{ $name }}</h4>
                                            <p class="text-[11px] text-gray-500 mt-0.5 capitalize">
                                                Varian: {{ $color }}{{ $size ? ' • ' . $size : '' }}
                                            </p>
                                            <p class="text-[11px] text-gray-500 mt-1">Qty: <strong class="text-[#24201D]">{{ $quantity }}</strong></p>
                                        </div>

                                        <div class="text-right shrink-0 self-end">
                                            <span class="text-xs font-semibold text-[#24201D]">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">

                            <div class="space-y-3 text-xs pt-2">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal Produk ({{ $totalItemCount }} barang)</span>
                                    <span class="font-semibold text-[#24201D]">Rp {{ number_format($subtotal ?? $totalBelanjaCalculated, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Ongkos Kirim <span id="selected_courier_label" class="text-[#3A4A3B] font-medium"></span></span>
                                    <span class="font-semibold text-[#24201D]" id="shipping_cost_display">Rp 0</span>
                                </div>
                            </div>

                            <hr class="border-[#F3EFE6]">

                            <div class="flex justify-between items-baseline">
                                <div>
                                    <span class="font-serif text-base font-semibold text-[#24201D] block">Total Tagihan</span>
                                    <span class="text-[10px] text-gray-400">Termasuk PPN & Asuransi Pengiriman</span>
                                </div>
                                <span class="font-serif text-2xl font-bold text-[#24201D]" id="grand_total_display">
                                    Rp {{ number_format($subtotal ?? $totalBelanjaCalculated, 0, ',', '.') }}
                                </span>
                            </div>

                            <button type="submit" id="btn-submit-checkout" disabled
                                    class="w-full bg-[#3A4A3B] hover:bg-[#2C392D] disabled:bg-gray-300 disabled:cursor-not-allowed text-white py-4 rounded-full text-xs font-semibold tracking-wide transition duration-200 shadow-sm flex items-center justify-center gap-2">
                                <span>Bayar Sekarang via Midtrans (Instan)</span>
                                <span>→</span>
                            </button>

                            <div class="text-center text-[10px] text-gray-400 pt-1">
                                Dukungan Transaksi: <strong class="text-gray-600 font-medium">GoPay &nbsp;•&nbsp; QRIS &nbsp;•&nbsp; BCA/Mandiri VA</strong>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        @endif
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const courierSelect = document.getElementById('courier');
    const courierCards = document.querySelectorAll('.courier-card');
    const serviceSelect = document.getElementById('shipping_service');
    const shippingCostDisplay = document.getElementById('shipping_cost_display');
    const shippingCostInput = document.getElementById('shipping_cost_input');
    const selectedCourierLabel = document.getElementById('selected_courier_label');
    const grandTotalDisplay = document.getElementById('grand_total_display');
    const btnSubmit = document.getElementById('btn-submit-checkout');

    const subtotal = {{ $subtotal ?? $totalBelanjaCalculated }};
    const totalWeight = {{ $totalWeight ?? 1000 }};

    function resetCourierCards() {
        courierCards.forEach(card => {
            card.classList.remove('bg-[#3A4A3B]', 'border-[#3A4A3B]', 'ring-2', 'ring-[#3A4A3B]/30');
            card.classList.add('bg-[#F3EFE6]/60', 'border-[#D6C7B2]/60');
            card.querySelectorAll('span, p').forEach(el => {
                el.classList.remove('text-white', 'text-white/80');
            });
        });
    }

    function setCourierCardsDisabled(state) {
        courierCards.forEach(card => {
            card.disabled = state;
        });
    }

    provinceSelect.addEventListener('change', function () {
        const provinceId = this.value;
        citySelect.innerHTML = '<option value="">-- Memuat Kota... --</option>';
        citySelect.disabled = true;
        setCourierCardsDisabled(true);
        resetCourierCards();
        courierSelect.value = '';
        serviceSelect.innerHTML = '<option value="">-- Pilih Kota & Kurir Terlebih Dahulu --</option>';
        serviceSelect.disabled = true;
        btnSubmit.disabled = true;
        resetCosts();

        if (provinceId) {
            fetch(`/api/cities/${provinceId}`)
                .then(res => res.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">-- Pilih Kota / Kabupaten --</option>';
                    data.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.city_id}">${city.type} ${city.city_name}</option>`;
                    });
                    citySelect.disabled = false;
                })
                .catch(() => {
                    citySelect.innerHTML = '<option value="">-- Gagal Memuat Kota --</option>';
                });
        }
    });

    citySelect.addEventListener('change', function () {
        resetCourierCards();
        courierSelect.value = '';
        serviceSelect.innerHTML = '<option value="">-- Klik Kartu Kurir di Atas --</option>';
        serviceSelect.disabled = true;
        btnSubmit.disabled = true;
        resetCosts();

        if (this.value) {
            setCourierCardsDisabled(false);
        } else {
            setCourierCardsDisabled(true);
        }
    });

    courierCards.forEach(card => {
        card.addEventListener('click', function () {
            if (this.disabled) return;

            const selectedCourier = this.getAttribute('data-courier');
            courierSelect.value = selectedCourier;

            resetCourierCards();
            this.classList.remove('bg-[#F3EFE6]/60', 'border-[#D6C7B2]/60');
            this.classList.add('bg-[#3A4A3B]', 'border-[#3A4A3B]', 'ring-2', 'ring-[#3A4A3B]/30');
            this.querySelectorAll('span, p').forEach(el => {
                el.classList.add('text-white');
            });

            const cityId = citySelect.value;
            if (cityId && selectedCourier) {
                serviceSelect.innerHTML = '<option value="">-- Memuat Layanan... --</option>';
                serviceSelect.disabled = true;
                btnSubmit.disabled = true;
                resetCosts();

                fetch('/api/check-shipping-cost', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        city_id: cityId,
                        weight: totalWeight,
                        courier: selectedCourier
                    })
                })
                .then(res => res.json())
                .then(costs => {
                    serviceSelect.innerHTML = '<option value="">-- Pilih Layanan Pengiriman --</option>';
                    if (costs && costs.length > 0) {
                        costs.forEach(cost => {
                            const costValue = cost.cost[0].value;
                            const etd = cost.cost[0].etd ? ` (${cost.cost[0].etd} Hari)` : '';
                            serviceSelect.innerHTML += `
                                <option value="${costValue}" data-label="${selectedCourier.toUpperCase()} ${cost.service}">
                                    ${cost.service} (${cost.description}) — Rp ${costValue.toLocaleString('id-ID')}${etd}
                                </option>
                            `;
                        });
                        serviceSelect.disabled = false;
                    } else {
                        serviceSelect.innerHTML = '<option value="">-- Layanan Tidak Tersedia --</option>';
                    }
                })
                .catch(() => {
                    serviceSelect.innerHTML = '<option value="">-- Gagal Memuat Layanan --</option>';
                });
            }
        });
    });

    serviceSelect.addEventListener('change', function () {
        const cost = parseInt(this.value) || 0;
        const selectedOption = this.options[this.selectedIndex];
        const serviceLabel = selectedOption ? selectedOption.getAttribute('data-label') : '';

        if (cost > 0) {
            shippingCostDisplay.textContent = 'Rp ' + cost.toLocaleString('id-ID');
            shippingCostInput.value = cost;
            selectedCourierLabel.textContent = serviceLabel ? `(${serviceLabel})` : '';

            const grandTotal = subtotal + cost;
            grandTotalDisplay.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
            btnSubmit.disabled = false;
        } else {
            resetCosts();
            btnSubmit.disabled = true;
        }
    });

    function resetCosts() {
        shippingCostDisplay.textContent = 'Rp 0';
        shippingCostInput.value = 0;
        selectedCourierLabel.textContent = '';
        grandTotalDisplay.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    }
});
</script>
@endsection