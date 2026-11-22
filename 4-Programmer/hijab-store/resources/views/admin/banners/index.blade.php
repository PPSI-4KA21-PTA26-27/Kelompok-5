@extends('layouts.admin')

@section('title', 'Hero Banner & Konten')

@section('content')
<div class="py-8 px-4 sm:px-8 max-w-7xl mx-auto space-y-6">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-3xl text-[#24201D] font-bold tracking-tight">Hero Banner</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola gambar banner promosi beranda, headline, tautan tombol, dan urutan tampil.</p>
        </div>

        <a href="{{ route('admin.banners.create') }}"
           class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-2xs flex items-center gap-1.5 shrink-0 w-fit">
            <span>+ Tambah Banner</span>
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-[#D1FAE5] border border-[#A7F3D0] text-[#065F46] px-4 py-3 rounded-xl text-xs font-medium flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#065F46] font-bold text-sm">×</button>
        </div>
    @endif

    <!-- Quick Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Banner Terdaftar</p>
                <h3 class="font-serif text-2xl font-bold text-[#24201D] mt-1">{{ $banners->count() }} <span class="text-xs font-sans font-normal text-gray-400">Slide</span></h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#FAF7F2] border border-[#D6C7B2]/50 flex items-center justify-center text-[#3A4A3B] text-base">
                🖼️
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Banner Aktif (Tampil)</p>
                <h3 class="font-serif text-2xl font-bold text-[#3A4A3B] mt-1">
                    {{ $banners->where('is_active', true)->count() }} <span class="text-xs font-sans font-normal text-gray-400">Aktif</span>
                </h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#FAF7F2] border border-[#D6C7B2]/50 flex items-center justify-center text-[#3A4A3B] text-base">
                ✅
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Banner Disembunyikan</p>
                <h3 class="font-serif text-2xl font-bold text-gray-500 mt-1">
                    {{ $banners->where('is_active', false)->count() }} <span class="text-xs font-sans font-normal text-gray-400">Draft</span>
                </h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#FAF7F2] border border-[#D6C7B2]/50 flex items-center justify-center text-gray-400 text-base">
                🙈
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Banner -->
    <div class="bg-white rounded-2xl border border-[#D6C7B2]/50 shadow-[0_2px_12px_-3px_rgba(36,32,29,0.03)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#D6C7B2]/40 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-[#FAF7F2]/50">
                        <th class="py-4 px-6 w-20 text-center">URUTAN</th>
                        <th class="py-4 px-6">PREVIEW BANNER</th>
                        <th class="py-4 px-6">KONTEN & TAUTAN TOMBOL</th>
                        <th class="py-4 px-6">STATUS</th>
                        <th class="py-4 px-6 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3EFE6] text-xs text-[#24201D]">
                    @if($banners->count() > 0)
                        @foreach($banners as $banner)
                            <tr class="hover:bg-[#FAF7F2]/60 transition">
                                <!-- Urutan -->
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-[#FAF7F2] border border-[#D6C7B2]/60 font-mono text-xs font-bold text-[#24201D]">
                                        {{ $banner->sort_order }}
                                    </span>
                                </td>

                                <!-- Preview Gambar -->
                                <td class="py-4 px-6">
                                    <div class="w-36 h-20 rounded-xl overflow-hidden bg-[#F3EFE6] border border-[#D6C7B2]/50">
                                        @if($banner->image)
                                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-[10px]">No Image</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Konten -->
                                <td class="py-4 px-6 max-w-md">
                                    @if($banner->badge)
                                        <span class="inline-block bg-[#F3EFE6] text-[#3A4A3B] text-[9px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-1">
                                            {{ $banner->badge }}
                                        </span>
                                    @endif
                                    <h4 class="font-semibold text-xs text-[#24201D] leading-snug">
                                        {{ $banner->title }}
                                    </h4>
                                    @if($banner->subtitle)
                                        <p class="text-[11px] text-gray-400 line-clamp-1 mt-0.5">{{ $banner->subtitle }}</p>
                                    @endif
                                    <div class="mt-2 flex items-center gap-2 text-[10px] text-gray-400">
                                        <span class="bg-[#FAF7F2] border border-[#D6C7B2]/50 px-2 py-0.5 rounded text-[#24201D] font-medium">
                                            {{ $banner->button_text ?? 'Jelajahi Koleksi' }}
                                        </span>
                                        <span class="font-mono truncate max-w-[160px]">
                                            → {{ $banner->button_link ?? '/katalog' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-6">
                                    @if($banner->is_active)
                                        <span class="inline-block bg-[#DCFCE7] text-[#15803D] text-[10px] font-semibold px-3 py-1 rounded-full">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-block bg-gray-100 text-gray-500 text-[10px] font-semibold px-3 py-1 rounded-full">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center gap-3 text-xs font-semibold">
                                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="text-[#24201D] hover:text-[#3A4A3B] transition">
                                            Ubah
                                        </a>

                                        <span class="text-gray-300">•</span>

                                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus banner &quot;{{ $banner->title }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400 font-serif text-base">
                                Belum ada banner yang ditambahkan. Klik "+ Tambah Banner" untuk mulai mengatur beranda.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        <div class="px-6 py-4 bg-[#FAF7F2]/50 border-t border-[#D6C7B2]/40 flex justify-between items-center text-xs text-gray-500">
            <span>Menampilkan <strong>{{ $banners->count() }}</strong> banner</span>
        </div>
    </div>

</div>
@endsection