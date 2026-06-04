@extends('layouts.app')

@section('title', 'Edit Mata Kuliah')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-edit text-amber-600 text-lg"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Mata Kuliah</h1>
        </div>
        <p class="text-gray-500 ml-14">Perbaharui informasi mata kuliah</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-2">
                <i class="fas fa-info-circle text-teal-600"></i>
                <span class="font-semibold text-gray-700">Informasi Mata Kuliah</span>
            </div>
        </div>
        
        <form method="POST" action="{{ route('matakuliah.update', $matakuliah->id) }}">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-5">
                <!-- Kode Mata Kuliah -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">
                        <i class="fas fa-code text-teal-500 mr-2"></i>Kode Mata Kuliah
                    </label>
                    <input type="text" name="kode_mk" value="{{ old('kode_mk', $matakuliah->kode_mk) }}" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition"
                        placeholder="Contoh: MK101" required>
                    @error('kode_mk')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nama Mata Kuliah -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">
                        <i class="fas fa-book text-teal-500 mr-2"></i>Nama Mata Kuliah
                    </label>
                    <input type="text" name="nama_mk" value="{{ old('nama_mk', $matakuliah->nama_mk) }}" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition"
                        placeholder="Contoh: Pemrograman Web" required>
                    @error('nama_mk')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- SKS -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">
                        <i class="fas fa-calculator text-teal-500 mr-2"></i>Jumlah SKS
                    </label>
                    <select name="sks" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition">
                        <option value="1" {{ old('sks', $matakuliah->sks) == 1 ? 'selected' : '' }}>1 SKS</option>
                        <option value="2" {{ old('sks', $matakuliah->sks) == 2 ? 'selected' : '' }}>2 SKS</option>
                        <option value="3" {{ old('sks', $matakuliah->sks) == 3 ? 'selected' : '' }}>3 SKS</option>
                        <option value="4" {{ old('sks', $matakuliah->sks) == 4 ? 'selected' : '' }}>4 SKS</option>
                        <option value="6" {{ old('sks', $matakuliah->sks) == 6 ? 'selected' : '' }}>6 SKS</option>
                    </select>
                    @error('sks')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Semester -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">
                        <i class="fas fa-calendar-alt text-teal-500 mr-2"></i>Semester
                    </label>
                    <div class="relative">
                        <select name="semester" class="w-full px-4 py-3 border border-gray-200 rounded-xl appearance-none bg-white cursor-pointer focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition">
                            <option value="Ganjil" {{ old('semester', $matakuliah->semester) == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                            <option value="Genap" {{ old('semester', $matakuliah->semester) == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                    @error('semester')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="px-6 py-4 bg-gray-50/50 flex justify-end gap-3 rounded-b-2xl">
                <a href="{{ route('matakuliah.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-100 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl font-semibold text-white shadow-md flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Update Mata Kuliah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection