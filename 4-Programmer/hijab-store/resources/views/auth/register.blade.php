@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col sm:justify-center items-center py-10 px-4">
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-sm border border-support2/30 rounded-3xl overflow-hidden">
        
        <div class="text-center mb-8">
            <h2 class="font-serif text-3xl text-gray-900">Buat Akun Baru</h2>
            <p class="text-xs text-gray-500 mt-1">Bergabunglah dengan keluarga Shea daily</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                    class="w-full bg-support1/40 border border-support2/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-red-500" />
            </div>

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1 uppercase tracking-wider">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                    class="w-full bg-support1/40 border border-support2/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1 uppercase tracking-wider">Password</label>
                <input type="password" name="password" required autocomplete="new-password" 
                    class="w-full bg-support1/40 border border-support2/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1 uppercase tracking-wider">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password" 
                    class="w-full bg-support1/40 border border-support2/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-red-500" />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-xl text-sm font-medium hover:bg-opacity-90 transition shadow-sm">
                    Daftar Akun →
                </button>
            </div>

            <div class="text-center pt-4 border-t border-support2/20 text-xs text-gray-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-secondary font-semibold hover:underline">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>
@endsection