@extends('layouts.app')

@section('title', 'Kelola Mata Kuliah')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Kelola Mata Kuliah</h1>
            <p class="text-gray-500 mt-1">Tambah, edit, atau hapus data mata kuliah</p>
        </div>
        <a href="{{ route('matakuliah.create') }}" class="btn-primary px-5 py-2.5 rounded-xl font-semibold text-white shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Tambah Mata Kuliah
        </a>
    </div>

    <!-- Mata Kuliah Ganjil -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-sun text-amber-600"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-800">Semester Ganjil</h2>
                    <p class="text-sm text-gray-500">Mata kuliah yang tersedia pada semester ganjil</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">SKS</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($matakuliahGanjil as $mk)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-teal-50 text-teal-700 rounded-lg text-xs font-mono">{{ $mk->kode_mk }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-book text-gray-400 text-sm"></i>
                                <span class="text-gray-800">{{ $mk->nama_mk }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold">{{ $mk->sks }} SKS</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('matakuliah.edit', $mk->id) }}" class="px-3 py-1.5 bg-amber-500 text-white rounded-lg text-xs hover:bg-amber-600 transition inline-flex items-center gap-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('matakuliah.destroy', $mk->id) }}" class="inline" onsubmit="return confirm('Hapus mata kuliah {{ $mk->nama_mk }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600 transition inline-flex items-center gap-1">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-2 block"></i>
                            <p>Belum ada mata kuliah untuk semester ganjil</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mata Kuliah Genap -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-moon text-blue-600"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-800">Semester Genap</h2>
                    <p class="text-sm text-gray-500">Mata kuliah yang tersedia pada semester genap</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">SKS</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($matakuliahGenap as $mk)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-teal-50 text-teal-700 rounded-lg text-xs font-mono">{{ $mk->kode_mk }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-book text-gray-400 text-sm"></i>
                                <span class="text-gray-800">{{ $mk->nama_mk }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold">{{ $mk->sks }} SKS</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('matakuliah.edit', $mk->id) }}" class="px-3 py-1.5 bg-amber-500 text-white rounded-lg text-xs hover:bg-amber-600 transition inline-flex items-center gap-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('matakuliah.destroy', $mk->id) }}" class="inline" onsubmit="return confirm('Hapus mata kuliah {{ $mk->nama_mk }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600 transition inline-flex items-center gap-1">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-2 block"></i>
                            <p>Belum ada mata kuliah untuk semester genap</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection