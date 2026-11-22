@extends('layouts.admin')

@section('title', 'Ubah Produk')

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-5xl mx-auto space-y-6">

    <!-- Back Link & Title -->
    <div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-[#24201D] transition mb-3">
            <span>← Kembali ke Daftar Produk</span>
        </a>
        <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Ubah Produk</h1>
        <p class="text-xs text-gray-500 mt-1">Perbarui informasi detail, kategori, media, dan stok varian produk hijab.</p>
    </div>

    <form id="edit-product-form" action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card 1: Informasi Umum Produk -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] space-y-6">
            <div class="flex items-center justify-between border-b border-[#F3EFE6] pb-4">
                <h2 class="font-serif text-lg font-bold text-[#24201D]">Informasi Umum Produk</h2>
                <span class="text-[11px] text-gray-400 italic">* Kolom wajib diisi</span>
            </div>

            <!-- Baris 1: Nama Produk & Kategori -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Nama produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-500" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" required
                            class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">
                        <option value="">Pilih Kategori Produk</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-1 text-xs text-red-500" />
                </div>
            </div>

            <!-- Baris 2: Harga & Berat -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs text-gray-400 font-medium">Rp</span>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="1"
                               class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl pl-10 pr-4 py-2.5 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">
                    </div>
                    <x-input-error :messages="$errors->get('price')" class="mt-1 text-xs text-red-500" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Berat Pengiriman (Gram) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" required min="1"
                               class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">
                        <span class="absolute right-3.5 top-2.5 text-[11px] text-gray-400">gram</span>
                    </div>
                    <x-input-error :messages="$errors->get('weight')" class="mt-1 text-xs text-red-500" />
                </div>
            </div>

            <!-- Baris 3: Deskripsi & Foto Produk -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Deskripsi Produk <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="5" required
                              class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl p-3.5 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">{{ old('description', $product->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1 text-xs text-red-500" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Foto Produk Utama (Cover)
                    </label>
                    <div class="border border-dashed border-[#D6C7B2] bg-[#FAF7F2]/60 rounded-2xl p-4 flex items-center gap-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-20 h-24 object-cover rounded-xl border border-[#D6C7B2]/60 shrink-0">
                        @else
                            <div class="w-20 h-24 rounded-xl bg-white border border-[#D6C7B2]/60 flex items-center justify-center text-[10px] text-gray-400 shrink-0">
                                No Pic
                            </div>
                        @endif
                        <div class="space-y-2 flex-1 min-w-0">
                            <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                                   class="w-full text-[11px] text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#3A4A3B] file:text-white hover:file:bg-[#2C392D] cursor-pointer">
                            <p class="text-[10px] text-gray-400">Pilih foto baru jika ingin mengganti cover utama (Maks. 2MB).</p>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('image')" class="mt-1 text-xs text-red-500" />
                </div>
            </div>
        </div>

        <!-- Card 2: Varian (warna, ukuran, stok) -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#F3EFE6] pb-4">
                <div>
                    <h2 class="font-serif text-lg font-bold text-[#24201D]">Varian (warna, ukuran, stok)</h2>
                    <p class="text-xs text-gray-500">Kelola kombinasi warna, ukuran, SKU otomatis, dan jumlah stok tersedia.</p>
                </div>
                <button type="button" id="add-color-btn"
                        class="bg-white border border-[#D6C7B2] hover:bg-[#FAF7F2] text-[#24201D] text-xs font-semibold px-4 py-2 rounded-xl transition shadow-2xs shrink-0">
                    + Tambah Varian
                </button>
            </div>

            <!-- Tabel Baris Varian -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#D6C7B2]/40 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            <th class="py-2.5 px-3">WARNA *</th>
                            <th class="py-2.5 px-3">UKURAN</th>
                            <th class="py-2.5 px-3">SKU</th>
                            <th class="py-2.5 px-3 w-28">STOK *</th>
                            <th class="py-2.5 px-3">FOTO VARIAN</th>
                            <th class="py-2.5 px-3 text-right w-16">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="variants-container" class="divide-y divide-[#F3EFE6] text-xs">
                        @foreach($product->variants as $index => $variant)
                            <tr class="variant-row">
                                <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                <td class="py-3 px-3">
                                    <input type="text" name="variants[{{ $index }}][color]" value="{{ old("variants.$index.color", $variant->color) }}" required
                                           class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
                                </td>
                                <td class="py-3 px-3">
                                    <input type="text" name="variants[{{ $index }}][size]" value="{{ old("variants.$index.size", $variant->size) }}" placeholder="115x115 cm"
                                           class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
                                </td>
                                <td class="py-3 px-3 font-mono text-[11px] text-gray-400">
                                    {{ $variant->sku ?? '-' }}
                                </td>
                                <td class="py-3 px-3">
                                    <input type="number" name="variants[{{ $index }}][stock]" value="{{ old("variants.$index.stock", $variant->stock) }}" min="0" required
                                           class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-center font-semibold text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2">
                                        @if($variant->image)
                                            <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->color }}" class="w-8 h-8 object-cover rounded-lg border border-[#D6C7B2]/60 shrink-0">
                                        @endif
                                        <input type="file" name="variants[{{ $index }}][image]" accept="image/*"
                                               class="w-full text-[11px] text-gray-500 bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl p-1.5">
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if(!($loop->first && count($product->variants) == 1))
                                        <button type="button" class="remove-row text-red-500 hover:text-red-700 text-xs font-semibold transition">Hapus</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Action Footer -->
            <div class="pt-4 border-t border-[#F3EFE6] flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-[#D6C7B2] text-xs font-semibold text-gray-600 hover:bg-[#FAF7F2] transition">
                    Batal
                </a>
                <button type="submit"
                        class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold px-6 py-2.5 rounded-xl transition shadow-2xs flex items-center gap-2">
                    <span>✓ Simpan Perubahan</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
    let variantIndex = {{ count($product->variants) }};

    document.getElementById('add-color-btn').addEventListener('click', function() {
        const container = document.getElementById('variants-container');
        const newRow = document.createElement('tr');
        newRow.className = 'variant-row';

        newRow.innerHTML = `
            <td class="py-3 px-3">
                <input type="text" name="variants[${variantIndex}][color]" placeholder="Rosewood" required
                       class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
            </td>
            <td class="py-3 px-3">
                <input type="text" name="variants[${variantIndex}][size]" placeholder="115x115 cm"
                       class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
            </td>
            <td class="py-3 px-3 font-mono text-[11px] text-gray-400">
                Auto SKU
            </td>
            <td class="py-3 px-3">
                <input type="number" name="variants[${variantIndex}][stock]" placeholder="15" min="0" required
                       class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-center font-semibold text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
            </td>
            <td class="py-3 px-3">
                <input type="file" name="variants[${variantIndex}][image]" accept="image/*"
                       class="w-full text-[11px] text-gray-500 bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl p-1.5">
            </td>
            <td class="py-3 px-3 text-right">
                <button type="button" class="remove-row text-red-500 hover:text-red-700 text-xs font-semibold transition">Hapus</button>
            </td>
        `;

        container.appendChild(newRow);
        variantIndex++;

        newRow.querySelector('.remove-row').addEventListener('click', function() {
            newRow.remove();
        });
    });

    document.querySelectorAll('.remove-row').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.variant-row').remove();
        });
    });
</script>
@endsection