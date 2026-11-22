@extends('layouts.app')

@section('content')
<div class="bg-[#FAF7F2] text-[#24201D] min-h-screen py-10 md:py-14">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 space-y-6">

        <!-- Header -->
        <div class="text-center space-y-2">
            <p class="text-[10px] tracking-[0.2em] uppercase text-[#8C9B86] font-bold">LAYANAN MANDIRI TAMU</p>
            <h1 class="font-serif text-3xl md:text-4xl text-[#24201D]">Lacak Pesanan Anda</h1>
            <p class="text-xs sm:text-sm text-gray-500">Masukkan Nomor Invoice untuk melihat status pembayaran dan nomor resi pengiriman.</p>
        </div>

        <!-- Form Pencarian -->
        <div class="bg-white p-6 rounded-3xl border border-[#D6C7B2]/50 shadow-[0_2px_15px_-4px_rgba(36,32,29,0.04)]">
            <form action="{{ route('order.track.search') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="text"
                       name="order_id"
                       placeholder="Contoh: INV-20261101-MWZS"
                       value="{{ old('order_id', request('invoice', isset($order) ? $order->id : '')) }}"
                       class="flex-1 px-4 py-3 bg-[#FAF7F2]/60 border border-[#D6C7B2] rounded-xl text-xs sm:text-sm text-[#24201D] focus:bg-white focus:ring-[#3A4A3B] focus:border-[#3A4A3B] focus:outline-none"
                       required>
                <button type="submit"
                        class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white border-none px-6 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider cursor-pointer transition shrink-0">
                    Cari Pesanan
                </button>
            </form>

            @if(session('error'))
                <div class="mt-4 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Hasil Pencarian Pesanan -->
        @if(isset($order))
            <div class="bg-white p-6 md:p-8 rounded-3xl border border-[#D6C7B2]/50 shadow-[0_2px_15px_-4px_rgba(36,32,29,0.04)] space-y-6">

                <!-- Header Invoice & 2 Badge Status -->
                <div class="flex flex-wrap justify-between items-start gap-3 pb-5 border-b border-[#F3EFE6]">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Nomor Invoice</p>
                        <h2 class="font-serif text-xl font-bold text-[#24201D] mt-0.5">{{ $order->id }}</h2>

                        <div class="mt-2">
                            @if(!empty($order->tracking_number))
                                <span class="inline-block bg-[#F3EFE6] border border-[#D6C7B2] text-[#3A4A3B] text-xs font-mono font-bold px-3 py-1 rounded-lg">
                                    No. Resi: {{ $order->tracking_number }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">
                                    Resi belum diinput oleh admin
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase {{ $order->payment_status == 'paid' ? 'bg-[#DCFCE7] text-[#15803D]' : 'bg-[#FEF3C7] text-[#B45309]' }}">
                            {{ $order->payment_status == 'paid' ? 'PAID / DIBAYAR' : 'UNPAID' }}
                        </span>
                        <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase bg-[#F3EFE6] text-[#24201D] border border-[#D6C7B2]/60">
                            {{ strtoupper($order->order_status) }}
                        </span>
                    </div>
                </div>

                <!-- Detail Informasi Pelanggan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs bg-[#FAF7F2]/70 p-4 rounded-2xl border border-[#D6C7B2]/40">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Penerima</span>
                        <span class="font-semibold text-[#24201D]">{{ $order->customer_name ?? ($order->user->name ?? '-') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">No. HP / WA</span>
                        <span class="font-semibold text-[#24201D] font-mono">{{ $order->customer_phone ?? '-' }}</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Alamat Pengiriman</span>
                        <span class="text-[#24201D]">{{ $order->shipping_address ?? '-' }}</span>
                    </div>
                    <div class="sm:col-span-2 pt-2 border-t border-[#D6C7B2]/40 flex justify-between items-center">
                        <span class="text-gray-500 font-medium">Total Pembayaran</span>
                        <span class="font-serif text-base font-bold text-[#3A4A3B]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Item Produk -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#24201D] pb-2.5 mb-3 border-b border-dashed border-[#D6C7B2]">
                        Detail Pesanan
                    </h3>
                    <div class="space-y-3">
                        @if(isset($order->orderItems) && $order->orderItems->count() > 0)
                            @foreach($order->orderItems as $item)
                                @php
                                    $variantColor = $item->productVariant->color ?? null;
                                    $variantSize = $item->productVariant->size ?? null;
                                @endphp
                                <div class="flex justify-between items-center text-xs">
                                    <div>
                                        <span class="font-semibold text-[#24201D]">{{ $item->productVariant->product->name ?? ($item->product_name ?? 'Produk') }}</span>
                                        <span class="text-gray-400 text-[11px]">(Qty: {{ $item->quantity }})</span>
                                        @if($variantColor || $variantSize)
                                            <span class="block text-[11px] text-gray-500 capitalize">
                                                {{ $variantColor }}{{ $variantSize ? ' • ' . $variantSize : '' }}
                                            </span>
                                        @endif
                                    </div>
                                    <strong class="text-[#24201D]">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Tombol Bayar Sekarang (Jika Unpaid) -->
                @if($order->payment_status !== 'paid' && $order->status !== 'paid')
                    <div class="pt-5 border-t border-[#F3EFE6] text-center">
                        <p class="text-xs text-gray-500 mb-3">
                            Pesanan ini belum dibayar. Klik tombol di bawah untuk menyelesaikan pembayaran via Midtrans.
                        </p>
                        <button id="pay-button"
                                class="bg-[#3A4A3B] hover:bg-[#2C392D] text-white border-none px-8 py-3.5 rounded-xl text-xs font-semibold uppercase tracking-wider cursor-pointer transition shadow-sm">
                            💳 Bayar Sekarang
                        </button>
                    </div>
                @endif

            </div>
        @endif

    </div>
</div>

@if(isset($order) && $order->payment_status !== 'paid' && $order->status !== 'paid')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const payButton = document.getElementById('pay-button');
            const snapToken = "{{ $order->snap_token }}";

            if (payButton && snapToken) {
                payButton.addEventListener('click', function () {
                    snap.pay(snapToken, {
                        onSuccess: function (result) {
                            alert("Pembayaran berhasil!");
                            location.reload();
                        },
                        onPending: function (result) {
                            alert("Menunggu pembayaran Anda.");
                            location.reload();
                        },
                        onError: function (result) {
                            alert("Pembayaran gagal!");
                        },
                        onClose: function () {
                            alert('Anda menutup jendela pembayaran sebelum selesai.');
                        }
                    });
                });
            }
        });
    </script>
@endif
@endsection