@extends('layouts.app')

@section('content')
<div class="bg-[#FAF7F2] text-[#24201D] min-h-screen py-10 md:py-16">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Badge Atas -->
        <div class="mb-4">
            <span class="inline-flex items-center gap-1.5 bg-[#F3EFE6] border border-[#D6C7B2]/60 text-[#3A4A3B] text-[10px] font-bold tracking-[0.16em] uppercase px-3.5 py-1.5 rounded-full">
                <span>•</span>
                <span>SHEA DAILY FABRIC CARE GUIDE</span>
            </span>
        </div>

        <!-- Hero Header Halaman -->
        <div class="max-w-2xl mb-10">
            <h1 class="font-serif text-4xl md:text-5xl text-[#24201D] font-normal tracking-tight mb-4 leading-tight">
                Panduan Merawat Serat Hijab Agar Tetap <span class="italic text-[#3A4A3B]">Lembut & Awet</span>
            </h1>
            <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                Kiat praktis mencuci, menjemur, menyetrika, dan menyimpan koleksi hijab premium Shea Daily—mulai dari Voal Ultrafine, Silk Pashmina, Crinkle Chiffon, hingga Rayon Bambu—agar kilau warna tetap murni dan serat kain senantiasa bernapas.
            </p>
        </div>

        <!-- Banner Foto Editorial + Kutipan -->
        <div class="bg-white rounded-3xl border border-[#D6C7B2]/50 overflow-hidden shadow-[0_2px_15px_-4px_rgba(36,32,29,0.05)] mb-16">
            <div class="bg-[#F3EFE6]/70 border-b border-[#D6C7B2]/40 px-5 py-2.5 flex items-center justify-between text-[11px] text-gray-500">
                <span class="font-medium">‹ &nbsp; Shea Daily — Panduan Perawatan Kain Hijab</span>
                <span>🔍</span>
            </div>

            <div class="relative h-[320px] md:h-[420px] overflow-hidden">
                <img src="https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?auto=format&fit=crop&w=1400&q=85"
                     alt="Serat Kain Hijab Shea Daily"
                     class="w-full h-full object-cover object-center">
                <div class="absolute inset-0 bg-gradient-to-t from-[#24201D]/80 via-[#24201D]/25 to-transparent"></div>

                <div class="absolute bottom-6 left-6 md:bottom-10 md:left-10 max-w-lg text-white">
                    <p class="text-[9px] tracking-[0.2em] uppercase text-white/75 font-semibold mb-1.5">
                        ATELIER FABRIC PROTOCOL
                    </p>
                    <p class="font-serif text-lg md:text-2xl italic leading-snug font-normal">
                        "Kain yang dirawat dengan sentuhan lembut mempertahankan keanggunan alaminya lebih lama."
                    </p>
                </div>
            </div>
        </div>

        <!-- SECTION 2: 4 PILAR PERAWATAN DASAR SERAT KAIN -->
        <div class="mb-20">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end border-b border-[#D6C7B2]/40 pb-6 mb-10 gap-4">
                <div>
                    <p class="text-[10px] tracking-[0.2em] uppercase text-[#8C9B86] font-bold mb-1.5">RITUAL HARIAN</p>
                    <h2 class="font-serif text-3xl text-[#24201D] font-normal">4 Pilar Perawatan Dasar Serat Kain</h2>
                </div>
                <p class="text-[11px] text-gray-500 max-w-xs md:text-right leading-relaxed">
                    Langkah fundamental yang berlaku universal untuk menjaga integritas tenun dan pigmen warna seluruh lini hijab Shea Daily.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Pilar 01 -->
                <div class="bg-white rounded-3xl border border-[#D6C7B2]/45 p-6 flex flex-col justify-between shadow-[0_2px_12px_-4px_rgba(36,32,29,0.04)]">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <span class="font-serif text-2xl text-[#D6C7B2] font-light">01</span>
                            <span class="w-8 h-8 rounded-xl bg-[#F3EFE6] text-[#3A4A3B] flex items-center justify-center text-sm">💧</span>
                        </div>
                        <h3 class="font-serif text-lg text-[#24201D] mb-2.5">Cara Mencuci</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-6">
                            Cuci tangan menggunakan air dingin atau suam kuku. Tuangkan deterjen cair ringan khusus pakaian halus atau sampo bayi. Jangan disikat, rendam maksimal 10 menit.
                        </p>
                    </div>
                    <div class="bg-[#F3EFE6]/80 border border-[#D6C7B2]/40 rounded-2xl p-3.5">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-[#3A4A3B] mb-1">CATATAN PENTING</p>
                        <p class="text-[11px] text-gray-600 leading-snug">Hindari deterjen bubuk tajam & pemutih pakaian.</p>
                    </div>
                </div>

                <!-- Pilar 02 -->
                <div class="bg-white rounded-3xl border border-[#D6C7B2]/45 p-6 flex flex-col justify-between shadow-[0_2px_12px_-4px_rgba(36,32,29,0.04)]">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <span class="font-serif text-2xl text-[#D6C7B2] font-light">02</span>
                            <span class="w-8 h-8 rounded-xl bg-[#F3EFE6] text-[#3A4A3B] flex items-center justify-center text-sm">🌬️</span>
                        </div>
                        <h3 class="font-serif text-lg text-[#24201D] mb-2.5">Menjemur & Kering</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-6">
                            Hindari memeras kain dengan pelintiran kencang. Cukup tepuk-tepuk lembut dengan handuk bersih, lalu jemur di area teduh berangin tanpa terik matahari langsung.
                        </p>
                    </div>
                    <div class="bg-[#F3EFE6]/80 border border-[#D6C7B2]/40 rounded-2xl p-3.5">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-[#3A4A3B] mb-1">CATATAN PENTING</p>
                        <p class="text-[11px] text-gray-600 leading-snug">Sinar UV langsung memudarkan pigmen alami kain.</p>
                    </div>
                </div>

                <!-- Pilar 03 -->
                <div class="bg-white rounded-3xl border border-[#D6C7B2]/45 p-6 flex flex-col justify-between shadow-[0_2px_12px_-4px_rgba(36,32,29,0.04)]">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <span class="font-serif text-2xl text-[#D6C7B2] font-light">03</span>
                            <span class="w-8 h-8 rounded-xl bg-[#F3EFE6] text-[#3A4A3B] flex items-center justify-center text-sm">♨️</span>
                        </div>
                        <h3 class="font-serif text-lg text-[#24201D] mb-2.5">Suhu Setrika</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-6">
                            Atur setrika pada mode Low-to-Medium Heat. Setrika dari sisi dalam kain, atau gunakan uap steamer pakaian vertikal untuk menjaga volume drape sutra dan crinkle.
                        </p>
                    </div>
                    <div class="bg-[#F3EFE6]/80 border border-[#D6C7B2]/40 rounded-2xl p-3.5">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-[#3A4A3B] mb-1">CATATAN PENTING</p>
                        <p class="text-[11px] text-gray-600 leading-snug">Gunakan pelindung kain katun tipis untuk satin silk.</p>
                    </div>
                </div>

                <!-- Pilar 04 -->
                <div class="bg-white rounded-3xl border border-[#D6C7B2]/45 p-6 flex flex-col justify-between shadow-[0_2px_12px_-4px_rgba(36,32,29,0.04)]">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <span class="font-serif text-2xl text-[#D6C7B2] font-light">04</span>
                            <span class="w-8 h-8 rounded-xl bg-[#F3EFE6] text-[#3A4A3B] flex items-center justify-center text-sm">🧥</span>
                        </div>
                        <h3 class="font-serif text-lg text-[#24201D] mb-2.5">Penyimpanan</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-6">
                            Gantung menggunakan gantungan khusus hijab berlapis beludru atau gulung melingkar pada laci. Hindari tumpukan tertindih tebal agar tidak meninggalkan bekas garis lipatan kaku.
                        </p>
                    </div>
                    <div class="bg-[#F3EFE6]/80 border border-[#D6C7B2]/40 rounded-2xl p-3.5">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-[#3A4A3B] mb-1">CATATAN PENTING</p>
                        <p class="text-[11px] text-gray-600 leading-snug">Simpan silica gel di lemari untuk cegah kelembapan.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION 3: PEDOMAN CEPAT DO'S & DON'TS -->
        <div class="pb-8">
            <div class="text-center max-w-lg mx-auto mb-10">
                <p class="text-[10px] tracking-[0.2em] uppercase text-[#8C9B86] font-bold mb-1.5">AT A GLANCE</p>
                <h2 class="font-serif text-3xl text-[#24201D] font-normal mb-2">Pedoman Cepat Do's & Don'ts</h2>
                <p class="text-xs text-gray-500">Penyelamat instan sebelum memulai perawatan hijab favorit Shea Daily kamu di rumah.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Kolom Kiri: Yang Dianjurkan (Do) -->
                <div class="bg-white rounded-3xl border border-[#D6C7B2]/50 p-6 md:p-8 shadow-[0_2px_14px_-4px_rgba(36,32,29,0.04)]">
                    <div class="border-b border-[#F3EFE6] pb-4 mb-6">
                        <h3 class="font-serif text-xl text-[#3A4A3B] flex items-center gap-2">
                            <span>👍</span>
                            <span>Yang Dianjurkan (Do)</span>
                        </h3>
                        <p class="text-[11px] text-gray-400 mt-1">Praktik terbaik untuk menjaga keindahan & kualitas serat</p>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-[#FAF7F2] border border-[#D6C7B2]/40 rounded-2xl p-4 flex items-start gap-3">
                            <span class="text-[#3A4A3B] font-bold text-sm mt-0.5">✓</span>
                            <div>
                                <h4 class="text-xs font-semibold text-[#24201D] mb-1">Gunakan Klip Magnet Hijab</h4>
                                <p class="text-[11px] text-gray-500 leading-relaxed">Klip magnetik tanpa jarum menjaga jalinan benang tetap utuh bebas lubang tusukan halus.</p>
                            </div>
                        </div>

                        <div class="bg-[#FAF7F2] border border-[#D6C7B2]/40 rounded-2xl p-4 flex items-start gap-3">
                            <span class="text-[#3A4A3B] font-bold text-sm mt-0.5">✓</span>
                            <div>
                                <h4 class="text-xs font-semibold text-[#24201D] mb-1">Rendam Maksimal 10 - 15 Menit</h4>
                                <p class="text-[11px] text-gray-500 leading-relaxed">Perendaman singkat sudah cukup mengangkat kotoran debu tanpa melarutkan pengunci serat.</p>
                            </div>
                        </div>

                        <div class="bg-[#FAF7F2] border border-[#D6C7B2]/40 rounded-2xl p-4 flex items-start gap-3">
                            <span class="text-[#3A4A3B] font-bold text-sm mt-0.5">✓</span>
                            <div>
                                <h4 class="text-xs font-semibold text-[#24201D] mb-1">Gunakan Deterjen Cair Lembut / Baby Soap</h4>
                                <p class="text-[11px] text-gray-500 leading-relaxed">Formula netral pH menjaga kemurnian tone warna pastel Shea Daily tetap cerah.</p>
                            </div>
                        </div>

                        <div class="bg-[#FAF7F2] border border-[#D6C7B2]/40 rounded-2xl p-4 flex items-start gap-3">
                            <span class="text-[#3A4A3B] font-bold text-sm mt-0.5">✓</span>
                            <div>
                                <h4 class="text-xs font-semibold text-[#24201D] mb-1">Gunakan Steamer Uap untuk Kerutan</h4>
                                <p class="text-[11px] text-gray-500 leading-relaxed">Uap vertikal meremajakan serat sutra dan voal tanpa risiko gosong atau mengkilap.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Yang Dihindari (Don't) -->
                <div class="bg-white rounded-3xl border border-[#D6C7B2]/50 p-6 md:p-8 shadow-[0_2px_14px_-4px_rgba(36,32,29,0.04)]">
                    <div class="border-b border-[#F3EFE6] pb-4 mb-6">
                        <h3 class="font-serif text-xl text-[#B85C4C] flex items-center gap-2">
                            <span>🚫</span>
                            <span>Yang Dihindari (Don't)</span>
                        </h3>
                        <p class="text-[11px] text-gray-400 mt-1">Kebiasaan yang memicu kerusakan serat pada hijab</p>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-[#FAF7F2] border border-[#D6C7B2]/40 rounded-2xl p-4 flex items-start gap-3">
                            <span class="text-[#B85C4C] font-bold text-sm mt-0.5">✕</span>
                            <div>
                                <h4 class="text-xs font-semibold text-[#24201D] mb-1">Menjemur Terpapar Sinar Matahari Terik</h4>
                                <p class="text-[11px] text-gray-500 leading-relaxed">Radiasi langsung membuat serat menjadi kaku rapuh dan pigmen warna mudah pudar kusam.</p>
                            </div>
                        </div>

                        <div class="bg-[#FAF7F2] border border-[#D6C7B2]/40 rounded-2xl p-4 flex items-start gap-3">
                            <span class="text-[#B85C4C] font-bold text-sm mt-0.5">✕</span>
                            <div>
                                <h4 class="text-xs font-semibold text-[#24201D] mb-1">Menyikat Permukaan Kain dengan Kasar</h4>
                                <p class="text-[11px] text-gray-500 leading-relaxed">Bulu sikat kaku akan mencabut filamen serat halus sehingga permukaan menjadi berbulu (pilling).</p>
                            </div>
                        </div>

                        <div class="bg-[#FAF7F2] border border-[#D6C7B2]/40 rounded-2xl p-4 flex items-start gap-3">
                            <span class="text-[#B85C4C] font-bold text-sm mt-0.5">✕</span>
                            <div>
                                <h4 class="text-xs font-semibold text-[#24201D] mb-1">Menyemprot Parfum Langsung ke Bahan Sutra</h4>
                                <p class="text-[11px] text-gray-500 leading-relaxed">Kandungan alkohol dan minyak wewangian meninggalkan noda lingkaran permanen pada satin & silk.</p>
                            </div>
                        </div>

                        <div class="bg-[#FAF7F2] border border-[#D6C7B2]/40 rounded-2xl p-4 flex items-start gap-3">
                            <span class="text-[#B85C4C] font-bold text-sm mt-0.5">✕</span>
                            <div>
                                <h4 class="text-xs font-semibold text-[#24201D] mb-1">Memeras Kain dengan Cara Dipelintir Keras</h4>
                                <p class="text-[11px] text-gray-500 leading-relaxed">Puntiran kasar merusak struktur simetris tepian jahit tepi maupun laser-cut Shea Daily.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>
</div>
@endsection