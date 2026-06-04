@extends('layouts.app')

@section('title', 'Registrasi Dosen')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12">
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-up">
            <!-- Header Gradient -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-6 py-6 text-center">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3 backdrop-blur-sm">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Registrasi Dosen</h2>
                <p class="text-teal-100 mt-1 text-sm">Buat akun dosen baru</p>
            </div>
            
            <!-- Form Body -->
            <div class="p-6 md:p-8">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-user text-teal-500 mr-2"></i>Nama Lengkap
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                            placeholder="Masukkan nama lengkap" required autofocus>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- NIDN (kolom tambahan) -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-id-card text-teal-500 mr-2"></i>NIDN
                        </label>
                        <input type="text" name="nidn" value="{{ old('nidn') }}" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                            placeholder="Masukkan NIDN (10-12 digit)" required>
                        <p class="text-xs text-gray-400 mt-1">Nomor Induk Dosen Nasional</p>
                        @error('nidn')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-envelope text-teal-500 mr-2"></i>Alamat Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                            placeholder="dosen@example.com" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-lock text-teal-500 mr-2"></i>Kata Sandi
                        </label>
                        <input type="password" name="password" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                            placeholder="Minimal 8 karakter" required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Konfirmasi Password -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-lock text-teal-500 mr-2"></i>Konfirmasi Kata Sandi
                        </label>
                        <input type="password" name="password_confirmation" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                            placeholder="Ulangi kata sandi" required>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold text-white shadow-lg flex items-center justify-center gap-2 transition-all duration-200">
                        <i class="fas fa-user-check"></i>
                        Daftar Sekarang
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-white text-gray-400">Sudah punya akun?</span>
                    </div>
                </div>
                
                <!-- Login Link -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 transition font-medium">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk ke Dashboard
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
    </div>
</div>
@endsection