@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col sm:justify-center items-center py-10 px-4">
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-sm border border-support2/30 rounded-3xl overflow-hidden">
        
        <div class="text-center mb-8">
            <h2 class="font-serif text-3xl text-gray-900">Reset Password</h2>
            <p class="text-xs text-gray-500 mt-1">Silakan masukkan password baru Anda</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1 uppercase tracking-wider">Email</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" 
                    class="w-full bg-support1/40 border border-support2/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1 uppercase tracking-wider">Password Baru</label>
                <input type="password" name="password" required autocomplete="new-password" 
                    class="w-full bg-support1/40 border border-support2/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1 uppercase tracking-wider">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password" 
                    class="w-full bg-support1/40 border border-support2/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-red-500" />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-xl text-sm font-medium hover:bg-opacity-90 transition shadow-sm">
                    Simpan Password Baru →
                </button>
            </div>
        </form>
    </div>
</div>
@endsection