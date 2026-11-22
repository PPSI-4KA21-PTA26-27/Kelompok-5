@extends('layouts.app')

@section('content')
<div class="bg-[#FAF7F2] text-[#24201D] min-h-screen py-12 md:py-16">
    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white p-8 md:p-12 rounded-3xl shadow-[0_2px_15px_-4px_rgba(36,32,29,0.04)] border border-[#D6C7B2]/50 space-y-6">

            <div class="w-16 h-16 bg-[#DCFCE7] text-[#15803D] rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
                ✓
            </div>

            <h1 class="font-serif text-3xl text-[#24201D]">Pesanan Berhasil Dibuat!</h1>
            <p class="text-gray-600 max-w-md mx-auto text-xs sm:text-sm leading-relaxed">
                Terima kasih telah berbelanja di <strong>Shea Daily</strong>. Nomor Invoice pesanan Anda adalah:
            </p>

            <div class="inline-block bg-[#F3EFE6] px-6 py-3 rounded-xl font-mono font-semibold text-[#24201D] text-lg border border-[#D6C7B2]/60">
                {{ $order->id }}
            </div>

            <div class="max-w-md mx-auto bg-[#FAF7F2] rounded-2xl p-5 text-left text-xs space-y-2.5 border border-[#D6C7B2]/40 mt-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">Penerima:</span>
                    <span class="font-semibold text-[#24201D]">{{ $order->customer_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Nomor HP/WA:</span>
                    <span class="font-semibold text-[#24201D]">{{ $order->customer_phone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Pembayaran:</span>
                    <span class="font-bold text-[#24201D] text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-[#D6C7B2]/40">
                    <span class="text-gray-500">Status Pembayaran:</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold tracking-wider {{ $order->payment_status == 'paid' ? 'bg-[#DCFCE7] text-[#15803D]' : 'bg-[#FEF3C7] text-[#B45309]' }}">
                        {{ $order->payment_status }}
                    </span>
                </div>
            </div>

            <p class="text-xs text-gray-500">
                Simpan nomor Invoice ini untuk melacak status pesanan Anda.
            </p>

            <div class="pt-4 flex flex-col sm:flex-row justify-center items-center gap-3">
                @if($order->payment_status == 'unpaid' && $order->snap_token)
                    <button id="pay-button" class="w-full sm:w-auto bg-[#3A4A3B] hover:bg-[#2C392D] text-white px-8 py-3.5 rounded-xl text-xs font-semibold uppercase tracking-wider transition duration-200 shadow-sm">
                        Bayar Sekarang (Midtrans)
                    </button>
                @endif

                <a href="{{ route('order.track', ['invoice' => $order->id]) }}" class="w-full sm:w-auto bg-[#F3EFE6] border border-[#D6C7B2] text-[#24201D] hover:bg-[#EFECE6] px-6 py-3.5 rounded-xl text-xs font-semibold transition">
                    Lacak Pesanan
                </a>

                <a href="{{ url('/') }}" class="w-full sm:w-auto bg-white border border-[#D6C7B2]/60 text-gray-600 hover:text-[#24201D] px-6 py-3.5 rounded-xl text-xs font-medium transition">
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </section>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function () {
            snap.pay('{{ $order->snap_token }}', {
                onSuccess: function (result) {
                    window.location.reload();
                },
                onPending: function (result) {
                    window.location.reload();
                },
                onError: function (result) {
                    alert('Pembayaran gagal, silakan coba lagi.');
                },
                onClose: function () {
                    alert('Anda menutup pop-up pembayaran sebelum menyelesaikan transaksi.');
                }
            });
        });
    }
</script>
@endsection