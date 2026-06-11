@extends('layouts.app')

@section('title', 'Registrasi Mahasiswa')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12">
    <div class="w-full max-w-2xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-up">
            <!-- Header Gradient -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-6 py-8 text-center relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                <div class="relative">
                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                        <i class="fas fa-user-plus text-white text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Registrasi Mahasiswa</h2>
                    <p class="text-teal-100 mt-1 text-sm">Buat akun untuk mengakses sistem KRS</p>
                </div>
            </div>
            
            <!-- Form Body -->
            <div class="p-6 md:p-8">
                <form method="POST" action="{{ route('mahasiswa.register') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Upload Foto Profil - Paling Atas -->
                    <div class="mb-8 pb-6 border-b border-gray-100">
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <div id="fotoPreview" class="w-28 h-28 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center overflow-hidden shadow-lg ring-4 ring-teal-100">
                                    <i class="fas fa-user-graduate text-teal-500 text-4xl"></i>
                                </div>
                                <label for="foto_profil" class="absolute bottom-0 right-0 bg-teal-500 text-white p-2 rounded-full cursor-pointer hover:bg-teal-600 transition shadow-md">
                                    <i class="fas fa-camera text-sm"></i>
                                </label>
                                <input type="file" name="foto_profil" id="foto_profil" class="hidden" accept="image/jpeg,image/jpg,image/png">
                            </div>
                            <p class="text-xs text-gray-400 mt-3">Foto Profil (Opsional)<br>JPG, JPEG, PNG (Max 2MB)</p>
                            @error('foto_profil')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">
                                <i class="fas fa-user text-teal-500 mr-2"></i>Nama Lengkap
                            </label>
                            <input type="text" name="nama" value="{{ old('nama') }}" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                                placeholder="Masukkan nama lengkap" required>
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- NIM -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">
                                <i class="fas fa-id-card text-teal-500 mr-2"></i>NIM
                            </label>
                            <input type="text" name="nim" value="{{ old('nim') }}" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                                placeholder="Masukkan NIM (unik)" required>
                            @error('nim')
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
                                placeholder="mahasiswa@student.ac.id" required>
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
                                placeholder="Minimal 6 karakter" required>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Konfirmasi Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">
                                <i class="fas fa-lock text-teal-500 mr-2"></i>Konfirmasi Kata Sandi
                            </label>
                            <input type="password" name="password_confirmation" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                                placeholder="Ulangi kata sandi" required>
                        </div>
                        
                        <!-- Informasi Tambahan (hanya untuk tampilan) -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">
                                <i class="fas fa-info-circle text-teal-500 mr-2"></i>Status
                            </label>
                            <div class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 text-gray-600">
                                <i class="fas fa-graduation-cap text-teal-500 mr-2"></i>Mahasiswa Aktif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Info Penting -->
                    <div class="mb-6 p-4 bg-teal-50 rounded-xl border border-teal-100">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-teal-500 mt-0.5"></i>
                            <div class="text-sm text-teal-700">
                                <p class="font-semibold mb-1">Informasi Penting:</p>
                                <ul class="list-disc list-inside text-xs space-y-1">
                                    <li>Dosen Pembimbing Akademik (PA) akan dipilih saat mengisi KRS</li>
                                    <li>Pastikan data yang dimasukkan sudah benar</li>
                                    <li>Email akan digunakan untuk login ke sistem</li>
                                    <li>Foto profil dapat diubah nanti di halaman profil</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold text-white shadow-lg flex items-center justify-center gap-2 transition-all duration-200 hover:shadow-xl">
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
                    <a href="{{ route('mahasiswa.login.form') }}" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 transition font-medium">
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

@push('scripts')
<script>
    // Preview foto profil sebelum upload
    const fotoInput = document.getElementById('foto_profil');
    const fotoPreview = document.getElementById('fotoPreview');
    
    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                fotoPreview.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
            };
            reader.readAsDataURL(file);
        } else if (file) {
            fotoPreview.innerHTML = `<i class="fas fa-file-image text-teal-500 text-4xl"></i>`;
        }
    });
</script>
@endpush
@endsection