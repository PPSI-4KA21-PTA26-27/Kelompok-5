@extends('layouts.admin')

@section('title', 'Kategori Produk')

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-7xl mx-auto space-y-6" x-data="{ editModal: false, editId: null, editName: '' }">

    <!-- Header -->
    <div>
        <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Kategori Produk</h1>
        <p class="text-xs text-gray-500 mt-1">Kelola klasifikasi etalase dan kategori koleksi hijab Shea Daily.</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-[#D1FAE5] border border-[#A7F3D0] text-[#065F46] px-4 py-3 rounded-xl text-xs font-medium flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-[#065F46] font-bold text-sm">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 font-bold text-sm">×</button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Grid 2 Kolom: Kiri Tabel Daftar Kategori, Kanan Form Tambah -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Tabel Daftar Kategori (2 Kolom) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] overflow-hidden">
            <div class="px-6 py-4 bg-[#FAF7F2]/60 border-b border-[#D6C7B2]/40 flex items-center justify-between">
                <h2 class="font-serif text-lg font-bold text-[#24201D]">Daftar Kategori</h2>
                <span class="text-[11px] text-gray-500">{{ $categories->count() }} kategori • {{ $categories->sum('products_count') }} produk</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#D6C7B2]/40 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-[#FAF7F2]/50">
                            <th class="py-3.5 px-6">NAMA KATEGORI</th>
                            <th class="py-3.5 px-6">SLUG</th>
                            <th class="py-3.5 px-6 text-center">JUMLAH PRODUK</th>
                            <th class="py-3.5 px-6 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3EFE6] text-xs text-[#24201D]">
                        @forelse($categories as $category)
                            <tr class="hover:bg-[#FAF7F2]/60 transition">
                                <td class="py-4 px-6 font-semibold text-[#24201D]">
                                    {{ $category->name }}
                                </td>
                                <td class="py-4 px-6 font-mono text-[11px] text-gray-400">
                                    {{ $category->slug }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block bg-[#FAF7F2] border border-[#D6C7B2]/60 text-[#24201D] font-semibold text-[11px] px-2.5 py-0.5 rounded-full">
                                        {{ $category->products_count }} produk
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center gap-3 text-xs font-semibold">
                                        <button type="button"
                                                @click="editModal = true; editId = {{ $category->id }}; editName = '{{ addslashes($category->name) }}'"
                                                class="text-[#24201D] hover:text-[#3A4A3B] transition">
                                            Ubah
                                        </button>

                                        <span class="text-gray-300">•</span>

                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori &quot;{{ $category->name }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-gray-400 font-serif text-base">
                                    Belum ada kategori produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Tambah Kategori (1 Kolom) -->
        <div class="bg-white rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] p-6 h-fit space-y-4">
            <div class="border-b border-[#F3EFE6] pb-3">
                <h2 class="font-serif text-lg font-bold text-[#24201D]">Tambah Kategori Baru</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">Tambahkan pengelompokan baru untuk katalog toko.</p>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="Contoh: Pashmina Silk"
                           class="w-full text-xs border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 bg-[#FAF7F2]/60 text-[#24201D] placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">
                    <p class="text-[10px] text-gray-400 mt-1.5">Slug URL akan dibuat otomatis dari nama kategori.</p>
                </div>

                <button type="submit" class="w-full bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold py-2.5 rounded-xl transition shadow-2xs">
                    + Simpan Kategori
                </button>
            </form>
        </div>

    </div>

    <!-- Modal Edit Kategori -->
    <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-2xs px-4">
        <div @click.away="editModal = false" class="bg-white rounded-2xl border border-[#D6C7B2] max-w-md w-full p-6 shadow-xl">
            <h3 class="font-serif text-xl font-bold text-[#24201D] mb-4">Ubah Nama Kategori</h3>
            <form :action="'/admin/categories/' + editId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-[#24201D] mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="editName" required
                           class="w-full text-xs border border-[#D6C7B2]/70 rounded-xl px-4 py-2.5 bg-[#FAF7F2]/60 text-[#24201D] focus:outline-none focus:ring-1 focus:ring-[#3A4A3B] focus:bg-white transition">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl border border-[#D6C7B2] text-xs font-semibold text-gray-600 hover:bg-[#FAF7F2] transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection