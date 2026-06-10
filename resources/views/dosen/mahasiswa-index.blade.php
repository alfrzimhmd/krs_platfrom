@extends('layouts.app')

@section('title', 'Daftar Mahasiswa Bimbingan')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-teal-600 text-lg"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Mahasiswa Bimbingan</h1>
        </div>
        <p class="text-gray-500 ml-14">Kelola data mahasiswa yang menjadi bimbingan Anda</p>
    </div>

    <!-- Card Tabel -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-list text-teal-600"></i>
                </div>
                <span class="font-semibold text-gray-700">Data Mahasiswa</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mahasiswas as $mahasiswa)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mahasiswa->nim }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $mahasiswa->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="px-2 py-1 text-xs rounded-full {{ $mahasiswa->semester_saat_ini == 'Ganjil' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                {{ $mahasiswa->semester_saat_ini }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <!-- Tombol Edit -->
                                <a href="{{ route('dosen.mahasiswa.edit', $mahasiswa->id) }}" 
                                   class="text-teal-600 hover:text-teal-800 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1">
                                    <i class="fas fa-edit text-xs"></i>
                                    <span>Edit</span>
                                </a>
                                
                                <!-- Form Hapus dengan DELETE method -->
                                <form action="{{ route('dosen.mahasiswa.destroy', $mahasiswa->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus mahasiswa {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})? Semua KRS mahasiswa ini juga akan dihapus.')" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-user-graduate text-4xl mb-3 block text-gray-300"></i>
                            Belum ada mahasiswa bimbingan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Card -->
    <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
            <div class="text-sm text-blue-700">
                <p class="font-semibold mb-1">Informasi:</p>
                <p>Menghapus mahasiswa akan menghapus seluruh data KRS yang bersangkutan. Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
    </div>
</div>
@endsection