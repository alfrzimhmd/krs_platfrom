@extends('layouts.app')

@section('title', 'Login Mahasiswa')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12">
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-up">
            <!-- Header Gradient -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-6 py-8 text-center">
                <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <i class="fas fa-user-graduate text-white text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white">Akses Mahasiswa</h2>
                <p class="text-teal-100 mt-1 text-sm">Masuk ke portal pengajuan KRS</p>
            </div>
            
            <!-- Form Body -->
            <div class="p-6 md:p-8">
                <form method="POST" action="{{ route('mahasiswa.login') }}">
                    @csrf
                    
                    <!-- Nama Lengkap -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-user text-teal-500 mr-2"></i>Nama Lengkap
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                            placeholder="Masukkan nama lengkap Anda">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- NIM -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-id-card text-teal-500 mr-2"></i>NIM
                        </label>
                        <input type="text" name="nim" value="{{ old('nim') }}" 
                            class="w-full px-4 py-3 border {{ $errors->has('nim') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200 input-focus"
                            placeholder="Masukkan NIM Anda">
                        @error('nim')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-2">
                                <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 flex-shrink-0"></i>
                                <p class="text-red-600 text-xs leading-relaxed">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Semester -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-calendar-alt text-teal-500 mr-2"></i>Jenis Semester
                        </label>
                        <div class="relative">
                            <select name="semester" id="jenis_semester" class="w-full px-4 py-3 border border-gray-200 rounded-xl appearance-none bg-white cursor-pointer focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200">
                                <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                                <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('semester')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Nomor Semester -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">
                            <i class="fas fa-list-ol text-teal-500 mr-2"></i>Semester Ke-
                        </label>
                        <div class="relative">
                            <select name="nomor_semester" id="nomor_semester" class="w-full px-4 py-3 border border-gray-200 rounded-xl appearance-none bg-white cursor-pointer focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all duration-200">
                                {{-- Diisi oleh JavaScript berdasarkan pilihan Ganjil/Genap --}}
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('nomor_semester')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
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
                        <span class="px-3 bg-white text-gray-400">atau</span>
                    </div>
                </div>
                
                <!-- Back to Home -->
                <div class="text-center">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-teal-600 transition text-sm flex items-center justify-center gap-1">
                        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Info Card -->
        <div class="mt-6 text-center text-sm text-gray-400">
            <p>Belum punya akun? Hubungi admin akademik untuk pendaftaran.</p>
        </div>
    </div>
</div>
@push('scripts')
<script>
    const jenisSemester = document.getElementById('jenis_semester');
    const nomorSemester = document.getElementById('nomor_semester');
    const oldNomor = {{ old('nomor_semester') ? intval(old('nomor_semester')) : 'null' }};

    function updateNomorSemester() {
        const isGanjil = jenisSemester.value === 'Ganjil';
        const options = isGanjil
            ? [1, 3, 5, 7, 9, 11, 13]
            : [2, 4, 6, 8, 10, 12, 14];

        nomorSemester.innerHTML = '';
        options.forEach(function(num) {
            const opt = document.createElement('option');
            opt.value = num;
            opt.textContent = 'Semester ' + num;
            if (oldNomor === num) opt.selected = true;
            nomorSemester.appendChild(opt);
        });
    }

    jenisSemester.addEventListener('change', updateNomorSemester);
    updateNomorSemester(); // Inisialisasi saat halaman dimuat
</script>
@endpush

@endsection