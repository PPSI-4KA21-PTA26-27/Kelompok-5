@extends('layouts.app')

@section('content')
<div class="bg-[#FAF7F2] text-[#24201D] min-h-screen">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

        <!-- Breadcrumb -->
        <nav class="flex flex-wrap items-center text-[10px] tracking-[0.16em] uppercase text-[#8C9B86] font-semibold mb-6 gap-2">
            <a href="{{ url('/') }}" class="hover:text-[#3A4A3B] transition">HOME</a>
            <span>/</span>
            <a href="{{ route('katalog.index') }}" class="hover:text-[#3A4A3B] transition">KATALOG</a>
            @if($product->category)
                <span>/</span>
                <a href="{{ route('katalog.index', ['category' => $product->category->slug]) }}" class="hover:text-[#3A4A3B] transition">
                    {{ strtoupper($product->category->name) }}
                </a>
            @endif
            <span>/</span>
            <span class="text-[#24201D]">{{ strtoupper($product->name) }}</span>
        </nav>

        <!-- Grid Utama (Kiri Gambar, Kanan Detail) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">

            <!-- KOLOM KIRI: GAMBAR (5 Kolom) -->
            <div class="lg:col-span-5">
                <div class="relative w-full overflow-hidden rounded-[2rem] bg-white border border-[#D6C7B2]/50 group shadow-sm cursor-zoom-in" style="aspect-ratio: 4 / 5;" id="image-container-trigger">
                    @php
                        $firstVariant = $product->variants->first();
                        $firstVariantWithImage = $product->variants->first(fn($v) => !empty($v->image));
                        $initialImage = $firstVariantWithImage ? $firstVariantWithImage->image : $product->image;
                    @endphp
                    <img id="main-product-image"
                         src="{{ $initialImage ? asset('storage/' . $initialImage) : '' }}"
                         alt="{{ $product->name }}"
                         class="absolute inset-0 w-full h-full object-cover object-center transition-all duration-500 hover:scale-105 {{ $initialImage ? '' : 'hidden' }}">

                    <div id="image-placeholder" class="absolute inset-0 w-full h-full flex flex-col items-center justify-center text-gray-400 bg-[#F3EFE6] p-4 text-center {{ $initialImage ? 'hidden' : '' }}">
                        <span class="text-4xl mb-2">🛍️</span>
                        <span class="text-xs font-medium text-gray-500">Belum ada foto</span>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: DETAIL PRODUK (7 Kolom) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- Header Teks -->
                <div>
                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#8C9B86] font-bold mb-2">
                        {{ strtoupper($product->category->name ?? 'SHEA DAILY COLLECTION') }} · BERAT {{ $product->weight }} GR
                    </p>
                    <h1 class="font-serif text-3xl md:text-4xl lg:text-[2.5rem] text-[#24201D] leading-tight mb-3">{{ $product->name }}</h1>
                </div>

                <!-- Kotak Harga Highlight -->
                <div class="bg-white rounded-2xl p-5 flex items-center justify-between border border-[#D6C7B2]/60 shadow-[0_2px_10px_-3px_rgba(36,32,29,0.03)]">
                    <div class="flex items-baseline gap-3">
                        <span class="font-serif text-2xl md:text-3xl font-bold text-[#24201D]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <span class="bg-[#F3EFE6] text-[#3A4A3B] border border-[#D6C7B2]/60 text-[10px] font-bold px-3 py-1 rounded-full tracking-wide">
                        SKU: <span id="variant-sku-display" class="font-mono">{{ $firstVariant->sku ?? '-' }}</span>
                    </span>
                </div>

                <!-- Deskripsi Produk -->
                <div class="text-xs sm:text-sm text-gray-600 leading-relaxed whitespace-pre-line py-1">
                    {{ $product->description }}
                </div>

                <!-- Form Interaktif -->
                @php
                    $defaultVariantId = $firstVariant->id ?? $product->id;
                @endphp
                <form id="add-to-cart-form" action="{{ route('cart.add', $defaultVariantId) }}" method="POST" class="space-y-6" data-base-route="{{ url('/keranjang/tambah') }}">
                    @csrf

                    <!-- Pemilihan Varian Warna & Ukuran -->
                    <div class="space-y-3 bg-white p-5 rounded-2xl border border-[#D6C7B2]/50">
                        <div class="flex flex-wrap justify-between items-center gap-2">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#24201D]">
                                Pilih Varian:
                                <span id="selected-color-name" class="font-semibold text-[#3A4A3B] ml-1 capitalize">
                                    {{ $firstVariant->color ?? '-' }}{{ !empty($firstVariant->size) ? ' (' . $firstVariant->size . ')' : '' }}
                                </span>
                            </label>
                            <span class="text-[10px] font-bold tracking-wider uppercase text-[#3A4A3B] bg-[#DCFCE7] px-2.5 py-0.5 rounded-full">
                                Stok Tersedia: <span id="variant-stock-display">{{ $firstVariant->stock ?? 0 }}</span> pcs
                            </span>
                        </div>

                        @if($product->variants->count() > 0)
                            <div class="flex flex-wrap gap-2.5 pt-1">
                                @foreach($product->variants as $variant)
                                    @php
                                        $variantImgPath = $variant->image ?: $product->image;
                                        $variantImgUrl = $variantImgPath ? asset('storage/' . $variantImgPath) : '';
                                        $variantLabel = $variant->color . (!empty($variant->size) ? ' — ' . $variant->size : '');
                                    @endphp

                                    <label class="cursor-pointer">
                                        <input type="radio" name="variant_id" value="{{ $variant->id }}"
                                               class="peer sr-only variant-radio"
                                               data-id="{{ $variant->id }}"
                                               data-color="{{ $variantLabel }}"
                                               data-sku="{{ $variant->sku ?? '-' }}"
                                               data-stock="{{ $variant->stock }}"
                                               data-image="{{ $variantImgUrl }}"
                                               {{ $loop->first ? 'checked' : '' }}>

                                        <span class="flex items-center justify-center min-w-[4rem] px-4 py-2 rounded-xl border border-[#D6C7B2] bg-[#FAF7F2]/60 text-xs font-medium text-[#24201D] peer-checked:border-[#3A4A3B] peer-checked:bg-[#3A4A3B] peer-checked:text-white hover:border-[#3A4A3B] transition shadow-2xs capitalize">
                                            {{ $variantLabel }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-red-500 italic">Varian warna & ukuran belum tersedia.</p>
                        @endif
                    </div>

                    <!-- Kuantitas & Status -->
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#24201D]">Jumlah:</span>
                            <div class="flex items-center border border-[#D6C7B2] rounded-xl bg-white overflow-hidden w-32 h-11 shadow-2xs">
                                <button type="button" id="btn-minus" class="w-10 h-full text-gray-500 hover:bg-[#FAF7F2] transition flex items-center justify-center border-r border-[#D6C7B2]/60 font-semibold select-none">−</button>
                                <input type="number" id="quantity-input" name="quantity" value="1" min="1" class="w-full text-center border-none text-sm focus:ring-0 p-0 font-semibold bg-transparent text-[#24201D] [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" id="btn-plus" class="w-10 h-full text-gray-500 hover:bg-[#FAF7F2] transition flex items-center justify-center border-l border-[#D6C7B2]/60 font-semibold select-none">+</button>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 text-xs font-semibold text-[#3A4A3B]">
                            <span class="w-2 h-2 rounded-full bg-[#3A4A3B]"></span> Siap Dikirim dari Jakarta Utara
                        </div>
                    </div>

                    <!-- Tombol Tambah ke Keranjang -->
                    <div class="pt-1">
                        <button type="submit" id="btn-add-to-cart" class="w-full bg-[#3A4A3B] text-white h-14 rounded-2xl text-sm font-semibold tracking-wide hover:bg-[#2C392D] transition-all flex items-center justify-center gap-3 shadow-md shadow-[#3A4A3B]/15 disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-gray-400 disabled:shadow-none">
                            <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span id="btn-cart-text">Tambah ke Keranjang — Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </button>
                    </div>
                </form>

                <!-- Keunggulan Layanan -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-[#D6C7B2]/50">
                    <div class="flex flex-col items-center text-center gap-1.5 bg-white/70 p-3.5 rounded-2xl border border-[#D6C7B2]/40">
                        <span class="text-base">🚚</span>
                        <p class="text-[10px] font-bold text-[#24201D] tracking-wide uppercase">Pengiriman Cepat</p>
                        <p class="text-[10px] text-gray-500">JNE, J&T, SiCepat</p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-1.5 bg-white/70 p-3.5 rounded-2xl border border-[#D6C7B2]/40">
                        <span class="text-base">🧵</span>
                        <p class="text-[10px] font-bold text-[#24201D] tracking-wide uppercase">Material Pilihan</p>
                        <p class="text-[10px] text-gray-500">Adem & Nyaman Dipakai</p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-1.5 bg-white/70 p-3.5 rounded-2xl border border-[#D6C7B2]/40">
                        <span class="text-base">🎁</span>
                        <p class="text-[10px] font-bold text-[#24201D] tracking-wide uppercase">Signature Box</p>
                        <p class="text-[10px] text-gray-500">Kemasan Eksklusif</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- MODAL LIGHTBOX OVERLAY FITUR CLICK TO ZOOM & DRAG TO PAN -->
<div id="image-modal" class="fixed inset-0 z-50 hidden bg-[#FAF7F2]/95 backdrop-blur-md flex flex-col justify-between py-6 px-4 select-none">

    <div class="w-full flex justify-between items-center max-w-6xl mx-auto px-4">
        <span id="modal-status-text" class="text-[11px] font-semibold tracking-widest uppercase text-gray-500">
            Klik gambar untuk Zoom • Tahan & Geser untuk Pan
        </span>
        <span id="modal-counter-display" class="text-xs font-bold text-[#24201D] bg-white/90 px-3 py-1 rounded-full border border-[#D6C7B2] shadow-2xs">
            1 / {{ $product->variants->count() }}
        </span>
    </div>

    <div class="relative max-w-4xl max-h-[75vh] w-full mx-auto flex items-center justify-center overflow-hidden rounded-2xl cursor-zoom-in" id="modal-zoom-container">
        <img id="modal-image"
             src=""
             alt="Product High Resolution View"
             class="max-h-[75vh] max-w-full object-contain rounded-xl shadow-2xl transition-transform duration-200 ease-out origin-center pointer-events-none">
    </div>

    <div class="flex items-center justify-center gap-5 pt-2">
        <button type="button" id="prev-modal-btn" class="w-12 h-12 rounded-full bg-white border border-[#D6C7B2] text-[#24201D] flex items-center justify-center shadow-lg hover:bg-[#FAF7F2] hover:scale-105 active:scale-95 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <button type="button" id="close-modal-btn" class="w-12 h-12 rounded-full bg-white border border-[#D6C7B2] text-[#24201D] flex items-center justify-center shadow-lg hover:bg-[#FAF7F2] hover:scale-105 active:scale-95 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <button type="button" id="next-modal-btn" class="w-12 h-12 rounded-full bg-white border border-[#D6C7B2] text-[#24201D] flex items-center justify-center shadow-lg hover:bg-[#FAF7F2] hover:scale-105 active:scale-95 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainImage = document.getElementById('main-product-image');
        const placeholder = document.getElementById('image-placeholder');
        const colorRadios = document.querySelectorAll('.variant-radio');
        const selectedColorName = document.getElementById('selected-color-name');
        const skuDisplay = document.getElementById('variant-sku-display');
        const stockDisplay = document.getElementById('variant-stock-display');
        const quantityInput = document.getElementById('quantity-input');
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        const cartForm = document.getElementById('add-to-cart-form');
        const btnAddToCart = document.getElementById('btn-add-to-cart');
        const btnCartText = document.getElementById('btn-cart-text');

        const imageContainerTrigger = document.getElementById('image-container-trigger');
        const imageModal = document.getElementById('image-modal');
        const modalImage = document.getElementById('modal-image');
        const modalZoomContainer = document.getElementById('modal-zoom-container');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const prevModalBtn = document.getElementById('prev-modal-btn');
        const nextModalBtn = document.getElementById('next-modal-btn');
        const modalCounterDisplay = document.getElementById('modal-counter-display');
        const modalStatusText = document.getElementById('modal-status-text');

        const productPriceFormatted = "{{ number_format($product->price, 0, ',', '.') }}";
        let currentStock = parseInt(document.querySelector('.variant-radio:checked')?.getAttribute('data-stock') || 0);

        function updateStockValidation() {
            let currentQty = parseInt(quantityInput.value) || 0;

            if (currentQty < 1) {
                currentQty = 1;
                quantityInput.value = 1;
            }

            if (currentQty > currentStock || currentStock === 0) {
                btnAddToCart.disabled = true;
                if (currentStock === 0) {
                    btnCartText.textContent = "Stok Habis (Out of Stock)";
                } else {
                    btnCartText.textContent = `Stok Tidak Mencukupi (Maks. ${currentStock})`;
                }
            } else {
                btnAddToCart.disabled = false;
                btnCartText.textContent = `Tambah ke Keranjang — Rp ${productPriceFormatted}`;
            }
        }

        colorRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                const newImageSrc = this.getAttribute('data-image');
                const colorName = this.getAttribute('data-color');
                const skuCode = this.getAttribute('data-sku');
                const variantId = this.getAttribute('data-id');
                currentStock = parseInt(this.getAttribute('data-stock') || 0);

                if (cartForm) {
                    const baseRoute = cartForm.getAttribute('data-base-route');
                    cartForm.action = `${baseRoute}/${variantId}`;
                }

                if (selectedColorName) selectedColorName.textContent = colorName;
                if (skuDisplay) skuDisplay.textContent = skuCode;
                if (stockDisplay) stockDisplay.textContent = currentStock;

                updateStockValidation();

                if (mainImage) {
                    mainImage.style.opacity = '0.4';
                    setTimeout(() => {
                        if (newImageSrc && newImageSrc.trim() !== '') {
                            mainImage.src = newImageSrc;
                            mainImage.classList.remove('hidden');
                            if (placeholder) placeholder.classList.add('hidden');
                        } else {
                            mainImage.classList.add('hidden');
                            if (placeholder) placeholder.classList.remove('hidden');
                        }
                        mainImage.style.opacity = '1';
                    }, 200);
                }
            });
        });

        if (btnMinus && btnPlus && quantityInput) {
            btnMinus.addEventListener('click', function () {
                let currentVal = parseInt(quantityInput.value) || 1;
                if (currentVal > 1) {
                    quantityInput.value = currentVal - 1;
                    updateStockValidation();
                }
            });

            btnPlus.addEventListener('click', function () {
                let currentVal = parseInt(quantityInput.value) || 1;
                if (currentVal < currentStock) {
                    quantityInput.value = currentVal + 1;
                    updateStockValidation();
                }
            });

            quantityInput.addEventListener('input', updateStockValidation);
        }

        let isZoomed = false;
        let isDragging = false;
        let hasMoved = false;
        let startX = 0, startY = 0;
        let originX = 50, originY = 50;

        function updateModalCounter() {
            const radiosArray = Array.from(colorRadios);
            const currentIndex = radiosArray.findIndex(r => r.checked);
            if (modalCounterDisplay) {
                modalCounterDisplay.textContent = `${currentIndex + 1} / ${radiosArray.length}`;
            }
        }

        function resetZoomState() {
            isZoomed = false;
            isDragging = false;
            hasMoved = false;
            modalImage.style.transform = 'scale(1)';
            modalImage.style.transformOrigin = 'center center';
            modalZoomContainer.classList.remove('cursor-grab', 'cursor-grabbing', 'cursor-zoom-out');
            modalZoomContainer.classList.add('cursor-zoom-in');
            if (modalStatusText) {
                modalStatusText.textContent = "Klik gambar untuk Zoom • Gunakan panah untuk varian";
            }
        }

        function openImageModal() {
            if (mainImage && !mainImage.classList.contains('hidden') && mainImage.src) {
                modalImage.src = mainImage.src;
                imageModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                updateModalCounter();
                resetZoomState();
            }
        }

        function closeImageModal() {
            imageModal.classList.add('hidden');
            document.body.style.overflow = '';
            resetZoomState();
        }

        function navigateVariant(direction) {
            const radiosArray = Array.from(colorRadios);
            const currentIndex = radiosArray.findIndex(r => r.checked);
            let targetIndex = currentIndex + direction;

            if (targetIndex >= radiosArray.length) targetIndex = 0;
            if (targetIndex < 0) targetIndex = radiosArray.length - 1;

            radiosArray[targetIndex].checked = true;
            radiosArray[targetIndex].dispatchEvent(new Event('change'));

            setTimeout(() => {
                modalImage.src = mainImage.src;
                updateModalCounter();
                resetZoomState();
            }, 200);
        }

        modalZoomContainer.addEventListener('mousedown', (e) => {
            startX = e.clientX;
            startY = e.clientY;
            hasMoved = false;

            if (isZoomed) {
                isDragging = true;
                modalZoomContainer.classList.remove('cursor-grab');
                modalZoomContainer.classList.add('cursor-grabbing');
            }
        });

        modalZoomContainer.addEventListener('mousemove', (e) => {
            if (Math.abs(e.clientX - startX) > 5 || Math.abs(e.clientY - startY) > 5) {
                hasMoved = true;
            }

            if (isZoomed && isDragging) {
                const rect = modalZoomContainer.getBoundingClientRect();
                const deltaX = ((e.clientX - startX) / rect.width) * 100;
                const deltaY = ((e.clientY - startY) / rect.height) * 100;

                originX = Math.max(0, Math.min(100, originX - (deltaX * 0.50)));
                originY = Math.max(0, Math.min(100, originY - (deltaY * 0.50)));

                modalImage.style.transformOrigin = `${originX}% ${originY}%`;
                startX = e.clientX;
                startY = e.clientY;
            }
        });

        modalZoomContainer.addEventListener('mouseup', (e) => {
            if (isDragging) {
                isDragging = false;
                modalZoomContainer.classList.remove('cursor-grabbing');
                modalZoomContainer.classList.add('cursor-grab');
            }

            if (!hasMoved) {
                if (!isZoomed) {
                    const rect = modalZoomContainer.getBoundingClientRect();
                    originX = ((e.clientX - rect.left) / rect.width) * 100;
                    originY = ((e.clientY - rect.top) / rect.height) * 100;

                    modalImage.style.transformOrigin = `${originX}% ${originY}%`;
                    modalImage.style.transform = 'scale(2.5)';
                    isZoomed = true;

                    modalZoomContainer.classList.remove('cursor-zoom-in');
                    modalZoomContainer.classList.add('cursor-grab');
                    if (modalStatusText) {
                        modalStatusText.textContent = "Tahan & Geser untuk Pan • Klik sekali lagi untuk Zoom Out";
                    }
                } else {
                    resetZoomState();
                }
            }
        });

        modalZoomContainer.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                hasMoved = false;

                if (isZoomed) {
                    isDragging = true;
                }
            }
        }, { passive: true });

        modalZoomContainer.addEventListener('touchmove', (e) => {
            if (e.touches.length === 1) {
                const touch = e.touches[0];
                if (Math.abs(touch.clientX - startX) > 5 || Math.abs(touch.clientY - startY) > 5) {
                    hasMoved = true;
                }

                if (isZoomed && isDragging) {
                    const rect = modalZoomContainer.getBoundingClientRect();
                    const deltaX = ((touch.clientX - startX) / rect.width) * 100;
                    const deltaY = ((touch.clientY - startY) / rect.height) * 100;

                    originX = Math.max(0, Math.min(100, originX - (deltaX * 0.2)));
                    originY = Math.max(0, Math.min(100, originY - (deltaY * 0.2)));

                    modalImage.style.transformOrigin = `${originX}% ${originY}%`;
                    startX = touch.clientX;
                    startY = touch.clientY;
                }
            }
        }, { passive: true });

        modalZoomContainer.addEventListener('touchend', (e) => {
            if (!hasMoved) {
                if (!isZoomed) {
                    const touch = e.changedTouches[0];
                    const rect = modalZoomContainer.getBoundingClientRect();
                    originX = ((touch.clientX - rect.left) / rect.width) * 100;
                    originY = ((touch.clientY - rect.top) / rect.height) * 100;

                    modalImage.style.transformOrigin = `${originX}% ${originY}%`;
                    modalImage.style.transform = 'scale(2.5)';
                    isZoomed = true;
                } else {
                    resetZoomState();
                }
            }
            isDragging = false;
        });

        if (imageContainerTrigger) imageContainerTrigger.addEventListener('click', openImageModal);
        if (closeModalBtn) closeModalBtn.addEventListener('click', closeImageModal);
        if (nextModalBtn) nextModalBtn.addEventListener('click', () => navigateVariant(1));
        if (prevModalBtn) prevModalBtn.addEventListener('click', () => navigateVariant(-1));

        document.addEventListener('keydown', function (e) {
            if (!imageModal.classList.contains('hidden')) {
                if (e.key === 'Escape') closeImageModal();
                if (e.key === 'ArrowRight') navigateVariant(1);
                if (e.key === 'ArrowLeft') navigateVariant(-1);
            }
        });

        updateStockValidation();
    });
</script>
@endsection