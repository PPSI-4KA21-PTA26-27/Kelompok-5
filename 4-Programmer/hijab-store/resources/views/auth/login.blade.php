@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-sm border border-support2/30 rounded-3xl overflow-hidden">
        
        <div class="text-center mb-8">
            <h2 class="font-serif text-3xl text-gray-900">Selamat Datang</h2>
            <p class="text-xs text-gray-500 mt-1">Masuk ke akun Shea daily Anda</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1 uppercase tracking-wider">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    class="w-full bg-support1/40 border border-support2/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1 uppercase tracking-wider">Password</label>
                <input type="password" name="password" required autocomplete="current-password" 
                    class="w-full bg-support1/40 border border-support2/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-sm">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-support2/50 text-primary shadow-sm focus:ring-primary">
                    <span class="ms-2 text-xs text-gray-600">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-xs text-secondary hover:underline" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <div>
                <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-xl text-sm font-medium hover:bg-opacity-90 transition shadow-sm">
                    Masuk →
                </button>
            </div>

            <div class="text-center pt-4 border-t border-support2/20 text-xs text-gray-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-secondary font-semibold hover:underline">Daftar sekarang</a>
            </div>
        </form>
    </div>
</div>
@endsection