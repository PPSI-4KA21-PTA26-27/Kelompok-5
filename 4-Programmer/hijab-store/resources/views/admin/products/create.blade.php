@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-5xl mx-auto space-y-6">

    <!-- Back Link & Title -->
    <div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-[#24201D] transition mb-3">
            <span>← Kembali ke Daftar Produk</span>
        </a>
        <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Tambah Produk</h1>
        <p class="text-xs text-gray-500 mt-1">Lengkapi informasi detail, media, dan manajemen stok varian produk hijab.</p>
    </div>

    <form id="create-product-form" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

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
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Voal Ultrafine Plain Scarf"
                           class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-xs text-[#24201D] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">
                    <p class="text-[10px] text-gray-400 mt-1">Gunakan nama yang jelas dan mencakup material utama.</p>
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">Kategori menentukan filter etalase di aplikasi toko.</p>
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
                        <input type="number" name="price" value="{{ old('price') }}" required min="1"
                               placeholder="129000"
                               class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl pl-10 pr-4 py-2.5 text-xs text-[#24201D] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">
                    </div>
                    <x-input-error :messages="$errors->get('price')" class="mt-1 text-xs text-red-500" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Berat Pengiriman (Gram) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="weight" value="{{ old('weight') }}" required min="1"
                               placeholder="150"
                               class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-xs text-[#24201D] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">
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
                              placeholder="Deskripsi bahan, karakteristik, dan petunjuk pemakaian..."
                              class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl p-3.5 text-xs text-[#24201D] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">{{ old('description') }}</textarea>
                    <p class="text-[10px] text-gray-400 mt-1">Sertakan instruksi perawatan hijab untuk meningkatkan kepercayaan pembeli.</p>
                    <x-input-error :messages="$errors->get('description')" class="mt-1 text-xs text-red-500" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Foto Produk Utama (Cover)
                    </label>
                    <div class="border border-dashed border-[#D6C7B2] bg-[#FAF7F2]/60 rounded-2xl p-6 text-center flex flex-col items-center justify-center h-[142px]">
                        <span class="text-xl mb-1 text-gray-400">🖼️</span>
                        <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                               class="text-[11px] text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-[#3A4A3B] file:text-white hover:file:bg-[#2C392D] cursor-pointer">
                        <p class="text-[10px] text-gray-400 mt-2">PNG, JPG, atau WEBP maks 2MB (Rekomendasi rasio 4:5)</p>
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
                    <p class="text-xs text-gray-500">Atur kombinasi spesifik warna, dimensi hijab, dan ketersediaan unit (SKU dibuat otomatis).</p>
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
                            <th class="py-2.5 px-3">UKURAN (OPSIONAL)</th>
                            <th class="py-2.5 px-3 w-28">STOK *</th>
                            <th class="py-2.5 px-3">FOTO VARIAN</th>
                            <th class="py-2.5 px-3 text-right w-16">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="variants-container" class="divide-y divide-[#F3EFE6] text-xs">
                        <!-- Baris Default Pertama -->
                        <tr class="variant-row">
                            <td class="py-3 px-3">
                                <input type="text" name="variants[0][color]" placeholder="Hitam / Charcoal" required
                                       class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
                            </td>
                            <td class="py-3 px-3">
                                <input type="text" name="variants[0][size]" placeholder="115x115 cm / All Size"
                                       class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
                            </td>
                            <td class="py-3 px-3">
                                <input type="number" name="variants[0][stock]" placeholder="24" min="0" required
                                       class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-center font-semibold text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
                            </td>
                            <td class="py-3 px-3">
                                <input type="file" name="variants[0][image]" accept="image/*"
                                       class="w-full text-[11px] text-gray-500 bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl p-1.5">
                            </td>
                            <td class="py-3 px-3 text-right">
                                <span class="text-[10px] text-gray-300">Wajib</span>
                            </td>
                        </tr>
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
                    <span>✓ Simpan Produk</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
    let variantIndex = 1;

    document.getElementById('add-color-btn').addEventListener('click', function() {
        const container = document.getElementById('variants-container');
        const newRow = document.createElement('tr');
        newRow.className = 'variant-row';

        newRow.innerHTML = `
            <td class="py-3 px-3">
                <input type="text" name="variants[${variantIndex}][color]" placeholder="Olive Sage" required
                       class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
            </td>
            <td class="py-3 px-3">
                <input type="text" name="variants[${variantIndex}][size]" placeholder="115x115 cm"
                       class="w-full bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-3 py-2 text-xs text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B]">
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
</script>
@endsection