@extends('layouts.app')

@section('title', 'Dashboard KRS')

@section('content')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
    }
    .thumbnail-preview {
        transition: all 0.2s ease;
    }
    .thumbnail-preview:hover {
        transform: scale(1.05);
    }
    .riwayat-item {
        transition: all 0.2s ease;
    }
    .riwayat-item:hover {
        background-color: #F8FAFC;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-menunggu {
        background-color: #FEF3C7;
        color: #D97706;
    }
    .status-disetujui {
        background-color: #D1FAE5;
        color: #059669;
    }
    .status-ditolak {
        background-color: #FEE2E2;
        color: #DC2626;
    }
    .btn-cetak {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        transition: all 0.2s ease;
    }
    .btn-cetak:hover {
        background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
        transform: scale(1.02);
        box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);
    }
</style>

<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header dengan Logout & Cetak -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard Mahasiswa</h1>
            <p class="text-gray-500 mt-1">Kelola Kartu Rencana Studi (KRS) Anda</p>
        </div>
        <div class="flex gap-3">
            <!-- Tombol Cetak KRS (hanya jika KRS disetujui) -->
            @if($krs && $krs->status == 'disetujui')
                <a href="{{ route('mahasiswa.cetak-krs') }}" target="_blank" class="flex items-center gap-2 px-4 py-2 btn-cetak text-white rounded-xl font-semibold shadow-md transition">
                    <i class="fas fa-print"></i>
                    <span>Cetak KRS</span>
                </a>
            @endif
            <form method="POST" action="{{ route('mahasiswa.logout') }}" id="logout-form-mahasiswa">
                @csrf
                <button type="button" onclick="confirmLogout()" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition shadow-sm">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Profil Mahasiswa Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-6 py-4">
            <h2 class="text-white font-semibold flex items-center gap-2">
                <i class="fas fa-user-circle"></i> Profil Mahasiswa
            </h2>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Foto Profil -->
                <div class="flex-shrink-0">
                    @if($mahasiswa->foto_profil && Storage::disk('public')->exists($mahasiswa->foto_profil))
                        <img src="{{ Storage::url($mahasiswa->foto_profil) }}" class="w-28 h-28 rounded-2xl object-cover shadow-lg ring-4 ring-teal-100">
                    @else
                        <div class="w-28 h-28 bg-gradient-to-br from-teal-100 to-teal-200 rounded-2xl flex items-center justify-center shadow-lg ring-4 ring-teal-100">
                            <i class="fas fa-user-graduate text-teal-500 text-4xl"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Informasi -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Nama Lengkap</p>
                        <p class="text-gray-800 font-semibold">{{ $mahasiswa->nama }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">NIM</p>
                        <p class="text-gray-800 font-semibold">{{ $mahasiswa->nim }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Email</p>
                        <p class="text-gray-800 font-semibold">{{ $mahasiswa->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Dosen Pembimbing Akademik</p>
                        <p class="text-gray-800 font-semibold">
                            @if($mahasiswa->dosen)
                                {{ $mahasiswa->dosen->nama_dosen }}
                            @else
                                <span class="text-amber-600">Belum dipilih</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="stat-card bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs">Total SKS</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalSksDitempuh }}</p>
                </div>
                <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calculator text-teal-600"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">SKS telah ditempuh</p>
        </div>
        <div class="stat-card bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs">Mata Kuliah</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalMatkulDitempuh }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book text-blue-600"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Telah ditempuh</p>
        </div>
        <div class="stat-card bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs">Pengajuan KRS</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPengajuan }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-purple-600"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Total pengajuan</p>
        </div>
        <div class="stat-card bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs">IPK Sementara</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($ipkSementara, 2) }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-amber-600"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Skala 4.00</p>
        </div>
    </div>

    <!-- Status Pengajuan KRS Aktif -->
    @if($krs && $krs->status != 'disetujui')
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-teal-500"></i>
                Status Pengajuan KRS Saat Ini
            </h2>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    @php
                        $statusClass = $krs->status == 'menunggu' ? 'status-menunggu' : 'status-ditolak';
                        $statusIcon = $krs->status == 'menunggu' ? 'fa-hourglass-half' : 'fa-times-circle';
                        $statusText = $krs->status == 'menunggu' ? 'Menunggu Persetujuan' : 'Ditolak';
                    @endphp
                    <div class="w-14 h-14 rounded-full flex items-center justify-center {{ $krs->status == 'menunggu' ? 'bg-amber-100' : 'bg-red-100' }}">
                        <i class="fas {{ $statusIcon }} {{ $krs->status == 'menunggu' ? 'text-amber-600' : 'text-red-600' }} text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Status Pengajuan</p>
                        <p class="font-bold text-xl {{ $krs->status == 'menunggu' ? 'text-amber-600' : 'text-red-600' }}">{{ $statusText }}</p>
                        <p class="text-xs text-gray-400">Semester {{ $krs->semester }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 text-sm">Total SKS</p>
                    <p class="font-bold text-gray-800 text-2xl">{{ $krs->total_sks }} <span class="text-gray-400 text-sm">SKS</span></p>
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-t border-gray-100">
                <p class="text-sm font-medium text-gray-700 mb-2">Mata Kuliah yang diajukan:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($krs->matakuliahs as $mk)
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm">
                            {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                        </span>
                    @endforeach
                </div>
            </div>
            
            @if($krs->bukti_ukt_path)
            <div class="mt-3">
                <a href="{{ Storage::url($krs->bukti_ukt_path) }}" target="_blank" class="text-teal-600 text-sm hover:underline flex items-center gap-1">
                    <i class="fas fa-file-pdf"></i> Lihat Bukti UKT
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Form KRS Card (hanya tampil jika belum ada KRS atau status masih menunggu) -->
    @if(!$krs || $krs->status == 'menunggu')
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-teal-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-edit text-teal-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-800 text-lg">
                        @if($existingKrs && $existingKrs->status == 'menunggu')
                            Edit Pengajuan KRS
                        @else
                            Form Pengajuan KRS
                        @endif
                    </h2>
                    <p class="text-sm text-gray-500">Isi form berikut untuk mengajukan KRS</p>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('mahasiswa.store') }}" enctype="multipart/form-data" id="krsForm">
            @csrf
            
            <div class="p-6 border-b border-gray-100">
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-chalkboard-user text-teal-500 mr-2"></i>
                    Dosen Pembimbing Akademik (PA)
                </label>
                <select name="dosen_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition" required>
                    <option value="">-- Pilih Dosen PA --</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ ($mahasiswa->dosen_id == $dosen->id || ($existingKrs && $existingKrs->mahasiswa->dosen_id == $dosen->id)) ? 'selected' : '' }}>
                            {{ $dosen->nama_dosen }} (NIDN: {{ $dosen->nidn }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-calendar-alt text-teal-500 mr-2"></i>
                            Jenis Semester
                        </label>
                        <select name="semester" id="jenis_semester" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition" required>
                            <option value="">-- Pilih Jenis Semester --</option>
                            <option value="Ganjil" {{ old('semester', $selectedSemester) == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                            <option value="Genap" {{ old('semester', $selectedSemester) == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-list-ol text-teal-500 mr-2"></i>
                            Semester Ke-
                        </label>
                        <select name="nomor_semester" id="nomor_semester" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition" required>
                            <option value="">-- Pilih Nomor Semester --</option>
                        </select>
                    </div>
                </div>
                <div id="semesterError" class="text-red-500 text-xs mt-2 hidden"></div>
            </div>
            
            <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-upload text-teal-500 mr-2"></i>
                    Bukti Pembayaran UKT
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-teal-400 transition cursor-pointer group" id="uploadArea">
                    <input type="file" name="bukti_ukt" id="fileInput" class="hidden" accept="image/jpeg,image/jpg,image/png,application/pdf">
                    <div id="uploadIcon" class="mb-3">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 group-hover:text-teal-500 transition"></i>
                    </div>
                    <p class="text-gray-500 group-hover:text-teal-600 transition">Klik atau drag file ke sini</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF (Max 2MB)</p>
                    <div id="fileName" class="mt-2 text-sm text-teal-600 hidden"></div>
                </div>
                <div id="thumbnailPreview" class="mt-4 hidden">
                    <div class="flex items-center gap-4 p-3 bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div id="thumbnailImage" class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden">
                            <i class="fas fa-file-pdf text-gray-400 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <p id="previewFileName" class="font-medium text-gray-700 text-sm"></p>
                            <p id="previewFileSize" class="text-xs text-gray-400"></p>
                        </div>
                        <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700 transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                @if($existingKrs && $existingKrs->bukti_ukt_path)
                    <div class="mt-3 p-3 bg-teal-50 rounded-xl border border-teal-200">
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <i class="fas fa-check-circle text-teal-600"></i>
                            File saat ini: 
                            <a href="{{ Storage::url($existingKrs->bukti_ukt_path) }}" target="_blank" class="text-teal-600 hover:underline">Lihat file</a>
                        </p>
                    </div>
                @endif
                @error('bukti_ukt')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="p-6 border-b border-gray-100">
                <label class="block text-gray-700 font-semibold mb-3">
                    <i class="fas fa-book-open text-teal-500 mr-2"></i>
                    Pilih Mata Kuliah <span class="text-sm text-gray-400 font-normal" id="semesterLabel">(Pilih semester terlebih dahulu)</span>
                </label>
                <div id="matakuliahList" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-1">
                    @if($matakuliahs && count($matakuliahs) > 0)
                        @foreach($matakuliahs as $mk)
                            <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:border-teal-300 hover:bg-teal-50/30 transition cursor-pointer group">
                                <input type="checkbox" name="matakuliah_ids[]" value="{{ $mk->id }}"
                                    {{ in_array($mk->id, $selectedMatakuliahs) ? 'checked' : '' }}
                                    class="mt-0.5 w-4 h-4 text-teal-600 rounded focus:ring-teal-500 mk-checkbox"
                                    data-sks="{{ $mk->sks }}" data-name="{{ $mk->kode_mk }} - {{ $mk->nama_mk }}">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 text-sm group-hover:text-teal-700 transition">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</p>
                                    <p class="text-xs text-gray-400">{{ $mk->sks }} SKS</p>
                                </div>
                            </label>
                        @endforeach
                    @else
                        <div class="col-span-2 text-center py-8 text-gray-400">
                            <i class="fas fa-info-circle text-4xl mb-2"></i>
                            <p>Silakan pilih semester terlebih dahulu untuk melihat mata kuliah</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="p-6 bg-gradient-to-r from-teal-50 to-teal-100/20">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-receipt text-teal-600"></i>
                    Ringkasan Pilihan Mata Kuliah
                </h3>
                <div id="selected-list" class="mb-4 text-sm text-gray-600 min-h-[80px] bg-white/50 rounded-xl p-3">
                    <p class="text-gray-400 italic flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Belum ada mata kuliah dipilih
                    </p>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-teal-200">
                    <span class="font-bold text-gray-700 text-lg">Total SKS</span>
                    <div class="text-right">
                        <span class="text-3xl font-bold text-teal-600" id="total-sks">0</span>
                        <span class="text-gray-400 ml-1">SKS</span>
                    </div>
                </div>
            </div>
            
            <div class="p-6 flex justify-end gap-3 bg-white">
                <button type="submit" class="btn-primary px-8 py-3 rounded-xl font-semibold text-white shadow-md flex items-center gap-2 transition-all">
                    <i class="fas fa-paper-plane"></i>
                    {{ $existingKrs ? 'Update KRS' : 'Kirim KRS' }}
                </button>
            </div>
        </form>
    </div>
    @elseif($krs && $krs->status == 'disetujui')
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-emerald-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Pengajuan KRS Telah Disetujui</h3>
            <p class="text-gray-500">Selamat! KRS Anda telah disetujui oleh dosen pembimbing akademik.</p>
            <p class="text-gray-400 text-sm mt-2">Total SKS: {{ $krs->total_sks }} SKS | Semester: {{ $krs->semester }}</p>
        </div>
    </div>
    @elseif($krs && $krs->status == 'ditolak')
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-times-circle text-red-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Pengajuan KRS Ditolak</h3>
            <p class="text-gray-500">Pengajuan KRS Anda telah ditolak oleh dosen pembimbing akademik.</p>
            <p class="text-gray-400 text-sm mt-2">Silakan hubungi dosen PA Anda untuk informasi lebih lanjut.</p>
            <p class="text-gray-400 text-sm mt-1">Total SKS: {{ $krs->total_sks }} SKS | Semester: {{ $krs->semester }}</p>
        </div>
    </div>
    @endif

    <!-- Riwayat Akademik -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-history text-teal-500"></i>
                Riwayat Akademik
            </h2>
            <p class="text-sm text-gray-500">Daftar mata kuliah yang telah disetujui sebelumnya</p>
        </div>
        <div class="p-6">
            @if($riwayatKrs && $riwayatKrs->count() > 0)
                <div class="space-y-4">
                    @foreach($riwayatKrs as $riwayat)
                        <div class="riwayat-item border border-gray-100 rounded-xl p-4">
                            <div class="flex flex-wrap items-center justify-between mb-3">
                                <div>
                                    <span class="font-semibold text-gray-800">Semester {{ $riwayat->semester }}</span>
                                    <span class="text-xs text-gray-400 ml-2">{{ $riwayat->created_at->format('d F Y') }}</span>
                                </div>
                                <span class="status-badge status-disetujui">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($riwayat->matakuliahs as $mk)
                                    <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm">
                                        {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="mt-3 text-right">
                                <span class="text-sm font-semibold text-teal-600">Total SKS: {{ $riwayat->total_sks }} SKS</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Belum ada riwayat akademik</p>
                    <p class="text-sm">Mata kuliah yang disetujui akan muncul di sini</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    let currentFile = null;
    
    const semesterGanjil = [1, 3, 5, 7, 9, 11, 13];
    const semesterGenap = [2, 4, 6, 8, 10, 12, 14];
    
    const jenisSemester = document.getElementById('jenis_semester');
    const nomorSemester = document.getElementById('nomor_semester');
    const matakuliahList = document.getElementById('matakuliahList');
    const semesterLabel = document.getElementById('semesterLabel');
    const semesterError = document.getElementById('semesterError');
    
    let selectedNomor = {{ old('nomor_semester', $selectedNomorSemester ?? 'null') }};
    let selectedSemester = '{{ old('semester', $selectedSemester ?? '') }}';
    
    function updateNomorSemesterOptions() {
        const isGanjil = jenisSemester.value === 'Ganjil';
        const options = isGanjil ? semesterGanjil : semesterGenap;
        
        nomorSemester.innerHTML = '<option value="">-- Pilih Nomor Semester --</option>';
        options.forEach(num => {
            const opt = document.createElement('option');
            opt.value = num;
            opt.textContent = 'Semester ' + num;
            if (selectedNomor == num && selectedSemester === jenisSemester.value) {
                opt.selected = true;
            }
            nomorSemester.appendChild(opt);
        });
    }
    
    async function loadMatakuliah() {
        const semester = jenisSemester.value;
        const nomor = nomorSemester.value;
        
        if (!semester || !nomor) {
            matakuliahList.innerHTML = `<div class="col-span-2 text-center py-8 text-gray-400">
                <i class="fas fa-info-circle text-4xl mb-2"></i>
                <p>Silakan pilih semester terlebih dahulu</p>
            </div>`;
            semesterLabel.textContent = '(Pilih semester terlebih dahulu)';
            semesterError.classList.add('hidden');
            return;
        }
        
        if (semester === 'Ganjil' && nomor % 2 === 0) {
            semesterError.textContent = 'Semester Ganjil hanya bisa memilih nomor semester ganjil (1, 3, 5, 7, 9, 11, 13).';
            semesterError.classList.remove('hidden');
            matakuliahList.innerHTML = `<div class="col-span-2 text-center py-8 text-red-400"><i class="fas fa-exclamation-triangle text-4xl mb-2"></i><p>${semesterError.textContent}</p></div>`;
            return;
        }
        if (semester === 'Genap' && nomor % 2 !== 0) {
            semesterError.textContent = 'Semester Genap hanya bisa memilih nomor semester genap (2, 4, 6, 8, 10, 12, 14).';
            semesterError.classList.remove('hidden');
            matakuliahList.innerHTML = `<div class="col-span-2 text-center py-8 text-red-400"><i class="fas fa-exclamation-triangle text-4xl mb-2"></i><p>${semesterError.textContent}</p></div>`;
            return;
        }
        
        semesterError.classList.add('hidden');
        semesterLabel.textContent = `(Semester ${semester} - Ke ${nomor})`;
        
        try {
            const response = await fetch('{{ route('mahasiswa.update-semester') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ semester: semester, nomor_semester: nomor })
            });
            const data = await response.json();
            if (data.success) {
                let html = '';
                data.matakuliahs.forEach(mk => {
                    const isChecked = {{ json_encode($selectedMatakuliahs) }}.includes(mk.id);
                    html += `<label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:border-teal-300 hover:bg-teal-50/30 transition cursor-pointer group">
                        <input type="checkbox" name="matakuliah_ids[]" value="${mk.id}" ${isChecked ? 'checked' : ''} class="mt-0.5 w-4 h-4 text-teal-600 rounded focus:ring-teal-500 mk-checkbox" data-sks="${mk.sks}" data-name="${mk.kode_mk} - ${mk.nama_mk}">
                        <div class="flex-1"><p class="font-medium text-gray-800 text-sm">${mk.kode_mk} - ${mk.nama_mk}</p><p class="text-xs text-gray-400">${mk.sks} SKS</p></div>
                    </label>`;
                });
                if (data.matakuliahs.length === 0) html = `<div class="col-span-2 text-center py-8 text-gray-400"><i class="fas fa-inbox text-4xl mb-2"></i><p>Belum ada mata kuliah untuk semester ${semester}</p></div>`;
                matakuliahList.innerHTML = html;
                attachCheckboxEvents();
            }
        } catch (error) { console.error('Error:', error); }
    }
    
    function attachCheckboxEvents() {
        document.querySelectorAll('.mk-checkbox').forEach(cb => {
            cb.removeEventListener('change', updateRingkasan);
            cb.addEventListener('change', updateRingkasan);
        });
        updateRingkasan();
    }
    
    jenisSemester.addEventListener('change', function() { updateNomorSemesterOptions(); loadMatakuliah(); });
    nomorSemester.addEventListener('change', loadMatakuliah);
    
    updateNomorSemesterOptions();
    if (selectedSemester && selectedNomor) {
        jenisSemester.value = selectedSemester;
        updateNomorSemesterOptions();
        loadMatakuliah();
    }
    
    // File upload
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    const previewFileName = document.getElementById('previewFileName');
    const previewFileSize = document.getElementById('previewFileSize');
    const thumbnailImage = document.getElementById('thumbnailImage');
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function previewFile(file) {
        if (!file) return;
        previewFileName.textContent = file.name;
        previewFileSize.textContent = formatFileSize(file.size);
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) { thumbnailImage.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`; };
            reader.readAsDataURL(file);
        } else {
            thumbnailImage.innerHTML = `<i class="fas fa-file-pdf text-gray-400 text-3xl"></i>`;
        }
        thumbnailPreview.classList.remove('hidden');
    }
    
    function clearFile() {
        fileInput.value = '';
        currentFile = null;
        thumbnailPreview.classList.add('hidden');
        fileName.classList.add('hidden');
        fileName.textContent = '';
    }
    
    if (uploadArea) {
        uploadArea.addEventListener('click', () => fileInput.click());
        uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('border-teal-500', 'bg-teal-50'); });
        uploadArea.addEventListener('dragleave', () => { uploadArea.classList.remove('border-teal-500', 'bg-teal-50'); });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-teal-500', 'bg-teal-50');
            const files = e.dataTransfer.files;
            if (files.length) { fileInput.files = files; previewFile(files[0]); fileName.textContent = `📄 ${files[0].name}`; fileName.classList.remove('hidden'); }
        });
        fileInput.addEventListener('change', (e) => { if (e.target.files.length) { previewFile(e.target.files[0]); fileName.textContent = `📄 ${e.target.files[0].name}`; fileName.classList.remove('hidden'); } });
    }
    
    function updateRingkasan() {
        let selected = [], total = 0;
        document.querySelectorAll('.mk-checkbox:checked').forEach(cb => {
            selected.push({ name: cb.dataset.name, sks: parseInt(cb.dataset.sks) });
            total += parseInt(cb.dataset.sks);
        });
        const listContainer = document.getElementById('selected-list');
        const totalSpan = document.getElementById('total-sks');
        if (selected.length) {
            let html = '<div class="space-y-2">';
            selected.forEach((item) => { html += `<div class="flex justify-between items-center py-1 border-b border-gray-100"><span class="text-gray-700">${item.name}</span><span class="text-gray-500 text-sm font-medium">${item.sks} SKS</span></div>`; });
            html += '</div>';
            listContainer.innerHTML = html;
        } else {
            listContainer.innerHTML = '<p class="text-gray-400 italic"><i class="fas fa-info-circle"></i> Belum ada mata kuliah dipilih</p>';
        }
        totalSpan.textContent = total;
    }
    
    function confirmLogout() {
        if (confirm('Apakah Anda yakin ingin logout?')) {
            document.getElementById('logout-form-mahasiswa').submit();
        }
    }
</script>
@endsection