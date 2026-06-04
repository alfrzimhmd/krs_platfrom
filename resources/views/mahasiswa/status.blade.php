@extends('layouts.app')

@section('title', 'Status KRS')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-8 text-center">
        <div class="w-16 h-16 bg-teal-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-clipboard-list text-teal-600 text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Status Pengajuan KRS</h1>
        <p class="text-gray-500 mt-1">Lihat status terbaru pengajuan KRS Anda</p>
    </div>
    
    @if($krs)
        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Status Header -->
            <div class="p-6 text-center border-b border-gray-100">
                @php
                    $statusConfig = [
                        'menunggu' => ['bg' => 'from-amber-400 to-amber-500', 'icon' => 'fa-hourglass-half', 'text' => 'Menunggu Persetujuan'],
                        'disetujui' => ['bg' => 'from-emerald-400 to-emerald-500', 'icon' => 'fa-check-circle', 'text' => 'Disetujui'],
                        'ditolak' => ['bg' => 'from-red-400 to-red-500', 'icon' => 'fa-times-circle', 'text' => 'Ditolak']
                    ];
                    $status = $statusConfig[$krs->status];
                @endphp
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r {{ $status['bg'] }} text-white shadow-md">
                    <i class="fas {{ $status['icon'] }}"></i>
                    <span class="font-semibold">{{ $status['text'] }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-3">Diajukan pada: {{ $krs->created_at->format('d F Y, H:i') }}</p>
            </div>
            
            <!-- Info Mahasiswa -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Nama Mahasiswa</p>
                        <p class="font-semibold text-gray-800">{{ $mahasiswa->nama }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">NIM</p>
                        <p class="font-semibold text-gray-800">{{ $mahasiswa->nim }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Semester</p>
                        <p class="font-semibold text-gray-800">{{ $krs->semester }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Dosen PA</p>
                        <p class="font-semibold text-gray-800">{{ $mahasiswa->dosen->nama_dosen ?? 'Belum dipilih' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Mata Kuliah -->
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-book-open text-teal-500"></i>
                    Mata Kuliah Dipilih
                </h3>
                <div class="space-y-2">
                    @foreach($krs->matakuliahs as $mk)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                            <div>
                                <p class="font-medium text-gray-800">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</p>
                                <p class="text-xs text-gray-400">{{ $mk->sks }} SKS</p>
                            </div>
                            <span class="text-teal-600 text-sm font-semibold">{{ $mk->sks }} SKS</span>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-200">
                    <span class="font-bold text-gray-700">Total SKS</span>
                    <span class="text-xl font-bold text-teal-600">{{ $krs->total_sks }} SKS</span>
                </div>
            </div>
            
            <!-- Bukti UKT -->
            @if($krs->bukti_ukt_path)
            <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-receipt text-teal-500"></i>
                    Bukti Pembayaran UKT
                </h3>
                <a href="{{ Storage::url($krs->bukti_ukt_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 text-teal-600 rounded-xl hover:bg-teal-100 transition">
                    <i class="fas fa-file-pdf"></i>
                    Lihat File Bukti UKT
                </a>
            </div>
            @endif
            
            <!-- Action Buttons -->
            <div class="p-6 flex justify-between gap-3">
                @if($krs->isEditable())
                    <a href="{{ route('mahasiswa.krs.form') }}" class="flex-1 text-center px-4 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i>
                        Edit KRS
                    </a>
                @endif
                <form method="POST" action="{{ route('mahasiswa.logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-inbox text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Pengajuan KRS</h3>
            <p class="text-gray-400 mb-6">Anda belum mengajukan KRS untuk semester ini</p>
            <a href="{{ route('mahasiswa.krs.form') }}" class="btn-primary px-6 py-3 rounded-xl text-white font-semibold inline-flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Buat KRS Sekarang
            </a>
            <div class="mt-6">
                <form method="POST" action="{{ route('mahasiswa.logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 text-sm">Logout</button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection