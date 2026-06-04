@extends('layouts.app')

@section('title', 'Edit Data Mahasiswa')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-edit text-teal-600 text-lg"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Data Mahasiswa</h1>
        </div>
        <p class="text-gray-500 ml-14">Perbaharui informasi mahasiswa bimbingan Anda</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-info-circle text-teal-600"></i>
                </div>
                <span class="font-semibold text-gray-700">Informasi Mahasiswa</span>
            </div>
        </div>
        
        <form method="POST" action="{{ route('dosen.mahasiswa.update', $mahasiswa->id) }}">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-5">
                <!-- Nama Mahasiswa -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">
                        <i class="fas fa-user text-teal-500 mr-2"></i>Nama Lengkap
                    </label>
                    <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition input-focus"
                        placeholder="Masukkan nama lengkap mahasiswa">
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- NIM -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">
                        <i class="fas fa-id-card text-teal-500 mr-2"></i>NIM
                    </label>
                    <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition input-focus"
                        placeholder="Masukkan NIM mahasiswa">
                    @error('nim')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Semester Saat Ini -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">
                        <i class="fas fa-calendar-alt text-teal-500 mr-2"></i>Semester Saat Ini
                    </label>
                    <div class="relative">
                        <select name="semester_saat_ini" class="w-full px-4 py-3 border border-gray-200 rounded-xl appearance-none bg-white cursor-pointer focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition">
                            <option value="Ganjil" {{ old('semester_saat_ini', $mahasiswa->semester_saat_ini) == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                            <option value="Genap" {{ old('semester_saat_ini', $mahasiswa->semester_saat_ini) == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                    @error('semester_saat_ini')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="p-6 bg-gray-50/50 flex justify-end gap-3">
                <a href="{{ route('dosen.mahasiswa.list') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl font-semibold text-white shadow-md flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    
    <!-- Info Card -->
    <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
            <div class="text-sm text-blue-700">
                <p class="font-semibold mb-1">Informasi:</p>
                <p>Perubahan data mahasiswa akan langsung diterapkan. Mahasiswa tetap dapat login menggunakan NIM yang baru.</p>
            </div>
        </div>
    </div>
</div>
@endsection