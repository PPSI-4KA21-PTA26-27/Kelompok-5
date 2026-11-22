@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-sm border border-support2/30 rounded-3xl overflow-hidden text-center">
        
        <div class="mb-6">
            <h2 class="font-serif text-2xl text-gray-900">Verifikasi Email Anda</h2>
            <p class="text-xs text-gray-500 mt-3 leading-relaxed">
                Terima kasih telah mendaftar! Sebelum mulai berbelanja, bisakah Anda memverifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan? Jika Anda tidak menerima email tersebut, dengan senang hati kami akan mengirimkan ulang.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-xs text-green-600 bg-green-50 p-3 rounded-xl border border-green-200">
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
            </div>
        @endif

        <div class="mt-6 space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl text-sm font-medium hover:bg-opacity-90 transition shadow-sm">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-xs text-gray-500 hover:text-gray-700 py-2 transition">
                    Keluar (Log Out)
                </button>
            </form>
        </div>
    </div>
</div>
@endsection