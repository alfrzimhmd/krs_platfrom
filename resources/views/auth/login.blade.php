@extends('layouts.app')

@section('title', 'Login Dosen')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12">
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-up">
            <!-- Header Gradient -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-6 py-8 text-center">
                <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <i class="fas fa-chalkboard-user text-white text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white">Portal Dosen</h2>
                <p class="text-teal-100 mt-1 text-sm">Masuk ke sistem pengelolaan KRS</p>
            </div>
            
            <!-- Form Body -->
            <div class="p-6 md:p-8">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-envelope text-teal-500 mr-2"></i>Alamat Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                            placeholder="dosen@example.com" required autofocus>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-lock text-teal-500 mr-2"></i>Kata Sandi
                        </label>
                        <input type="password" name="password" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                            placeholder="Masukkan kata sandi" required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-teal-600 rounded border-gray-300 focus:ring-teal-500">
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-teal-600 hover:text-teal-700 transition">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold text-white shadow-lg flex items-center justify-center gap-2 transition-all duration-200">
                        <i class="fas fa-arrow-right-to-bracket"></i>
                        Masuk ke Dashboard
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-white text-gray-400">Belum punya akun?</span>
                    </div>
                </div>
                
                <!-- Register Link -->
                <div class="text-center">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 transition font-medium">
                        <i class="fas fa-user-plus"></i>
                        Daftar sebagai Dosen
                    </a>
                </div>
                
                <!-- Back to Home -->
                <div class="mt-4 text-center">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-teal-600 transition text-sm flex items-center justify-center gap-1">
                        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Info Card -->
        <div class="mt-6 text-center text-sm text-gray-400">
            <p>Gunakan akun dosen yang sudah terdaftar untuk mengakses sistem.</p>
        </div>
    </div>
</div>
@endsection