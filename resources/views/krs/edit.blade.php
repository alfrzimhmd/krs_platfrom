@extends('layouts.app')

@section('title', 'Edit Pengajuan KRS')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-5 sm:mb-6 md:mb-8">
        <div class="flex items-center gap-3 sm:gap-4">
            <a href="{{ route('krs.index') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 p-2 sm:p-2.5 rounded-xl transition-all duration-200">
                <i class="fas fa-arrow-left text-white text-lg sm:text-xl"></i>
            </a>
            <div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-white">
                    <i class="fas fa-pen-to-square text-white/80 mr-2"></i> Edit Pengajuan KRS
                </h2>
                <p class="text-white/70 text-sm sm:text-base mt-1">Ubah data pengajuan rencana studi yang sudah ada</p>
            </div>
        </div>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-4 sm:p-6 md:p-8">
            <form action="{{ route('krs.update', $submission->id) }}" method="POST" class="space-y-4 sm:space-y-5 md:space-y-6">
                @csrf
                @method('PUT')

                <!-- Grid Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 md:gap-6">
                    
                    <!-- Nama Mahasiswa -->
                    <div class="md:col-span-2">
                        <label for="student_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-indigo-500 mr-1"></i> Nama Mahasiswa
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" 
                                   id="student_name"
                                   name="student_name" 
                                   value="{{ old('student_name', $submission->student_name) }}" 
                                   placeholder="Masukkan nama lengkap mahasiswa"
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('student_name') border-red-500 @enderror">
                        </div>
                        @error('student_name')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- NIM -->
                    <div>
                        <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-id-card text-indigo-500 mr-1"></i> NIM
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" 
                                   id="nim"
                                   name="nim" 
                                   value="{{ old('nim', $submission->nim) }}" 
                                   placeholder="Contoh: 202410101010"
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('nim') border-red-500 @enderror">
                        </div>
                        @error('nim')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Semester -->
                    <div>
                        <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-layer-group text-indigo-500 mr-1"></i> Semester
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                            </div>
                            <select name="semester" 
                                    id="semester"
                                    class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 appearance-none @error('semester') border-red-500 @enderror">
                                <option value="">Pilih Semester</option>
                                @for($i = 1; $i <= 14; $i++)
                                    <option value="{{ $i }}" {{ old('semester', $submission->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                @endfor
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                            </div>
                        </div>
                        @error('semester')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Total SKS -->
                    <div>
                        <label for="total_credits" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-chart-simple text-indigo-500 mr-1"></i> Total SKS
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calculator text-gray-400 text-sm"></i>
                            </div>
                            <input type="number" 
                                   id="total_credits"
                                   name="total_credits" 
                                   value="{{ old('total_credits', $submission->total_credits) }}" 
                                   placeholder="Contoh: 18"
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('total_credits') border-red-500 @enderror">
                        </div>
                        @error('total_credits')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status Persetujuan -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-flag-checkered text-indigo-500 mr-1"></i> Status Persetujuan
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400 text-sm"></i>
                            </div>
                            <select name="status" 
                                    id="status"
                                    class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 appearance-none @error('status') border-red-500 @enderror">
                                <option value="pending" {{ old('status', $submission->status) == 'pending' ? 'selected' : '' }}>⏳ Pending (Menunggu)</option>
                                <option value="approved" {{ old('status', $submission->status) == 'approved' ? 'selected' : '' }}>✅ Disetujui</option>
                                <option value="rejected" {{ old('status', $submission->status) == 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                            </div>
                        </div>
                        @error('status')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Daftar Mata Kuliah -->
                <div>
                    <label for="courses_list" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-book text-indigo-500 mr-1"></i> Daftar Mata Kuliah
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute top-3 left-3 pointer-events-none">
                            <i class="fas fa-list-ul text-gray-400 text-sm"></i>
                        </div>
                        <textarea name="courses_list" 
                                  id="courses_list"
                                  rows="5" 
                                  placeholder="Contoh:&#10;Pemrograman Web, Basis Data, Jaringan Komputer,&#10;Rekayasa Perangkat Lunak, Sistem Operasi"
                                  class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('courses_list') border-red-500 @enderror">{{ old('courses_list', $submission->courses_list) }}</textarea>
                    </div>
                    <div class="mt-2 flex flex-wrap gap-3 text-xs text-gray-500">
                        <span><i class="fas fa-info-circle mr-1"></i> Pisahkan setiap mata kuliah dengan tanda koma (,)</span>
                        <span><i class="fas fa-lightbulb mr-1"></i> Contoh: Pemrograman Web, Basis Data, Jaringan</span>
                    </div>
                    @error('courses_list')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Informasi Waktu Update -->
                <div class="bg-gray-50 rounded-xl p-3 sm:p-4 text-xs sm:text-sm text-gray-500 flex flex-wrap justify-between gap-2">
                    <span><i class="fas fa-clock mr-1"></i> Dibuat: {{ $submission->created_at->translatedFormat('d F Y H:i') }}</span>
                    <span><i class="fas fa-edit mr-1"></i> Terakhir diupdate: {{ $submission->updated_at->translatedFormat('d F Y H:i') }}</span>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('krs.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 text-center flex items-center justify-center gap-2 order-2 sm:order-1">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 order-1 sm:order-2">
                        <i class="fas fa-save"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Informasi Tambahan -->
    <div class="mt-4 sm:mt-6 bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-4">
        <div class="flex items-start gap-2 text-white/80 text-xs sm:text-sm">
            <i class="fas fa-exclamation-triangle mt-0.5"></i>
            <span>Perubahan data akan langsung tersimpan. Pastikan data yang diubah sudah benar.</span>
        </div>
    </div>
</div>

<!-- Auto Calculate SKS Hint -->
<script>
    document.getElementById('courses_list')?.addEventListener('blur', function() {
        let courses = this.value;
        if (courses) {
            let courseArray = courses.split(',').filter(c => c.trim().length > 0);
            let estimatedSKS = courseArray.length * 3;
            let sksField = document.getElementById('total_credits');
            if (sksField && !sksField.value) {
                sksField.value = estimatedSKS;
                let hint = document.createElement('div');
                hint.className = 'mt-1 text-xs text-green-600 flex items-center gap-1';
                hint.innerHTML = '<i class="fas fa-magic"></i> Estimasi SKS: ' + estimatedSKS + ' SKS (berdasarkan ' + courseArray.length + ' mata kuliah)';
                let parent = sksField.parentElement.parentElement;
                let oldHint = parent.querySelector('.sks-hint');
                if (oldHint) oldHint.remove();
                hint.classList.add('sks-hint');
                parent.appendChild(hint);
                setTimeout(() => hint.remove(), 3000);
            }
        }
    });
</script>
@endsection