@extends('layouts.admin')

@section('title', 'Tambah Hero Banner')

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-4xl mx-auto space-y-6">

    <!-- Back Link & Title -->
    <div>
        <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-[#24201D] transition mb-3">
            <span>← Kembali ke Daftar Banner</span>
        </a>
        <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Tambah Hero Banner</h1>
        <p class="text-xs text-gray-500 mt-1">Unggah gambar editorial dan atur teks promosi yang akan tampil di beranda.</p>
    </div>

    <!-- Alert Validasi Error -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs space-y-1">
            <p class="font-bold">Terjadi kesalahan input:</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-[#D6C7B2]/50 p-6 sm:p-8 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)]">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Upload Gambar Banner + Live Preview -->
            <div class="space-y-2">
                <label for="image" class="block text-xs font-semibold text-[#24201D]">
                    Gambar Hero Banner <span class="text-red-500">*</span>
                    <span class="text-[10px] font-normal text-gray-400">(Rekomendasi rasio landscape 16:9, maks. 2MB)</span>
                </label>

                <div id="image-preview-wrapper" class="relative w-full h-56 md:h-64 rounded-2xl bg-[#FAF7F2]/70 border-2 border-dashed border-[#D6C7B2] overflow-hidden flex flex-col items-center justify-center text-center p-4 transition">
                    <img id="image-preview" src="#" alt="Preview Banner" class="hidden absolute inset-0 w-full h-full object-cover">
                    <div id="image-placeholder" class="space-y-1.5">
                        <span class="text-2xl block">🖼️</span>
                        <p class="text-xs font-medium text-[#24201D]">Pilih file gambar untuk melihat pratinjau banner</p>
                        <p class="text-[10px] text-gray-400">Format: JPG, JPEG, PNG, atau WEBP</p>
                    </div>
                </div>

                <input type="file" name="image" id="image" accept="image/*" required
                       class="block w-full text-xs text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#3A4A3B] file:text-white hover:file:bg-[#2C392D] file:cursor-pointer cursor-pointer pt-1">
            </div>

            <hr class="border-[#F3EFE6]">

            <!-- Badge & Urutan Tampil -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="sm:col-span-2">
                    <label for="badge" class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Teks Badge / Label Atas <span class="text-[10px] font-normal text-gray-400">(Opsional)</span>
                    </label>
                    <input type="text" name="badge" id="badge" value="{{ old('badge') }}"
                           placeholder="Contoh: • Serene Modesty Collection 2026"
                           class="w-full text-xs bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-[#24201D] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] transition">
                </div>

                <div>
                    <label for="sort_order" class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Urutan Tampil
                    </label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full text-xs bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-[#24201D] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] transition">
                    <p class="text-[10px] text-gray-400 mt-1">Angka kecil tampil lebih dulu (0, 1, 2...)</p>
                </div>
            </div>

            <!-- Judul Utama -->
            <div>
                <label for="title" class="block text-xs font-semibold text-[#24201D] mb-1.5">
                    Judul Utama Banner (Headline) <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       placeholder="Contoh: Koleksi Esensial Harian yang Menenangkan"
                       class="w-full text-xs bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-[#24201D] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] transition">
            </div>

            <!-- Subjudul -->
            <div>
                <label for="subtitle" class="block text-xs font-semibold text-[#24201D] mb-1.5">
                    Subjudul / Deskripsi Singkat <span class="text-[10px] font-normal text-gray-400">(Opsional)</span>
                </label>
                <textarea name="subtitle" id="subtitle" rows="3"
                          placeholder="Sentuhan voal murni dan pashmina sutra bernapas alami. Dirancang untuk keanggunan bersahaja..."
                          class="w-full text-xs bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-[#24201D] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] transition">{{ old('subtitle') }}</textarea>
            </div>

            <!-- Teks Tombol & Link Tujuan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="button_text" class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Teks Tombol Utama
                    </label>
                    <input type="text" name="button_text" id="button_text" value="{{ old('button_text', 'Jelajahi Koleksi') }}"
                           placeholder="Contoh: Jelajahi Koleksi"
                           class="w-full text-xs bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-[#24201D] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] transition">
                </div>

                <div>
                    <label for="button_link" class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Tautan / URL Tujuan Tombol
                    </label>
                    <input type="text" name="button_link" id="button_link" value="{{ old('button_link', '/katalog') }}"
                           placeholder="Contoh: /katalog atau /best-seller"
                           class="w-full text-xs bg-[#FAF7F2]/60 border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 text-[#24201D] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] transition">
                </div>
            </div>

            <!-- Toggle Status Publikasi -->
            <div class="bg-[#FAF7F2] border border-[#D6C7B2]/50 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-[#24201D] block">Status Publikasi Banner</span>
                    <span class="text-[11px] text-gray-500">Aktifkan agar banner langsung muncul di halaman depan website.</span>
                </div>
                <label class="inline-flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="w-4 h-4 rounded border-[#D6C7B2] text-[#3A4A3B] focus:ring-[#3A4A3B] cursor-pointer">
                    <span class="text-xs font-semibold text-[#3A4A3B]">Aktifkan</span>
                </label>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 border-t border-[#F3EFE6] flex items-center justify-end gap-3">
                <a href="{{ route('admin.banners.index') }}" class="px-5 py-2.5 rounded-xl border border-[#D6C7B2] text-xs font-semibold text-gray-600 hover:bg-[#FAF7F2] transition">
                    Batal
                </a>
                <button type="submit" class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold px-6 py-2.5 rounded-xl transition shadow-2xs">
                    ✓ Simpan Banner
                </button>
            </div>

        </form>
    </div>

</div>

<script>
    document.getElementById('image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('image-placeholder');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    });
</script>
@endsection