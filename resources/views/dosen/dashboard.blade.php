@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<style>
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-4px);
    }
    .table-row-hover:hover {
        background-color: #F8FAFC;
        transition: all 0.2s ease;
    }
    .thumbnail-img {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .thumbnail-img:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .image-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        cursor: pointer;
        justify-content: center;
        align-items: center;
    }
    .image-modal.active {
        display: flex;
    }
    .image-modal img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 8px;
    }
    .image-modal .close-modal {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }
    .image-modal .close-modal:hover {
        color: #bbb;
    }
    
    .btn-detail {
        background: #0D9488;
        color: white;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-detail:hover {
        background: #0F766E;
        transform: scale(1.02);
        color: white;
    }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard Dosen</h1>
            <p class="text-gray-500 mt-1">Kelola pengajuan KRS mahasiswa bimbingan Anda</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-xl shadow-sm">
                <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-chalkboard-user text-white text-xs"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Dosen PA</p>
                    <p class="text-sm font-semibold text-gray-700">{{ $dosen->nama_dosen }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
        @php
            $totalMahasiswa = $mahasiswas->count();
            $totalKrs = $mahasiswas->sum(fn($m) => $m->krs->count());
            $menunggu = $mahasiswas->sum(fn($m) => $m->krs->where('status', 'menunggu')->count());
            $disetujui = $mahasiswas->sum(fn($m) => $m->krs->where('status', 'disetujui')->count());
            $ditolak = $mahasiswas->sum(fn($m) => $m->krs->where('status', 'ditolak')->count());
        @endphp
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div><p class="text-gray-400 text-sm">Total Mahasiswa</p><p class="text-2xl font-bold text-gray-800">{{ $totalMahasiswa }}</p></div>
                <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center"><i class="fas fa-users text-teal-600 text-lg"></i></div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div><p class="text-gray-400 text-sm">Total Pengajuan KRS</p><p class="text-2xl font-bold text-gray-800">{{ $totalKrs }}</p></div>
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-file-alt text-blue-600 text-lg"></i></div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div><p class="text-gray-400 text-sm">Menunggu</p><p class="text-2xl font-bold text-amber-600">{{ $menunggu }}</p></div>
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center"><i class="fas fa-hourglass-half text-amber-600 text-lg"></i></div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div><p class="text-gray-400 text-sm">Disetujui</p><p class="text-2xl font-bold text-emerald-600">{{ $disetujui }}</p></div>
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center"><i class="fas fa-check-circle text-emerald-600 text-lg"></i></div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div><p class="text-gray-400 text-sm">Ditolak</p><p class="text-2xl font-bold text-red-600">{{ $ditolak }}</p></div>
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center"><i class="fas fa-times-circle text-red-600 text-lg"></i></div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar KRS Mahasiswa Bimbingan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-teal-100 rounded-xl flex items-center justify-center"><i class="fas fa-clipboard-list text-teal-600"></i></div>
                <div><h2 class="font-bold text-gray-800">Daftar KRS Mahasiswa Bimbingan</h2><p class="text-sm text-gray-500">Lihat detail KRS mahasiswa</p></div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Total SKS</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Bukti UKT</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($mahasiswas as $mahasiswa)
                        @php 
                            $krs = $mahasiswa->krs->first();
                            $semType = $krs ? $krs->semester : ($mahasiswa->semester_saat_ini ?? null);
                            $semNum  = $mahasiswa->nomor_semester ?? null;
                            $semLabel = ($semType && $semNum) ? $semType . '/' . $semNum : ($semType ?? '-');
                        @endphp
                        <tr class="table-row-hover">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    @if($mahasiswa->foto_profil)
                                        <img src="{{ Storage::url($mahasiswa->foto_profil) }}"
                                             class="w-8 h-8 rounded-full object-cover border border-gray-200 shadow-sm thumbnail-img"
                                             alt="Foto {{ $mahasiswa->nama }}"
                                             onclick="openImageModal('{{ Storage::url($mahasiswa->foto_profil) }}')">
                                    @else
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-graduate text-gray-500 text-sm"></i>
                                        </div>
                                    @endif
                                    <span class="font-medium text-gray-800">{{ $mahasiswa->nama }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-gray-600">{{ $mahasiswa->nim }}</td>
                            <td class="px-4 py-4"><span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">{{ $semLabel }}</span></td>
                            <td class="px-4 py-4">
                                @if($krs && $krs->matakuliahs->count())
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($krs->matakuliahs->take(3) as $mk)
                                            <span class="px-2 py-0.5 bg-teal-50 text-teal-700 rounded-md text-xs">{{ $mk->kode_mk }}</span>
                                        @endforeach
                                        @if($krs->matakuliahs->count() > 3)
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-md text-xs">+{{ $krs->matakuliahs->count() - 3 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center"><span class="font-semibold text-gray-800">{{ $krs ? $krs->total_sks : 0 }}</span></td>
                            <td class="px-4 py-4 text-center">
                                @if($krs)
                                    @php
                                        $statusStyles = ['menunggu'=>'bg-amber-100 text-amber-700','disetujui'=>'bg-emerald-100 text-emerald-700','ditolak'=>'bg-red-100 text-red-700'];
                                        $statusIcons = ['menunggu'=>'fa-hourglass-half','disetujui'=>'fa-check-circle','ditolak'=>'fa-times-circle'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $statusStyles[$krs->status] }}">
                                        <i class="fas {{ $statusIcons[$krs->status] }} text-xs"></i>{{ ucfirst($krs->status) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">Belum mengajukan</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($krs && $krs->bukti_ukt_path)
                                    @php
                                        $filePath = Storage::url($krs->bukti_ukt_path);
                                        $isImage = in_array(pathinfo($krs->bukti_ukt_path, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif','webp']);
                                    @endphp
                                    @if($isImage)
                                        <div class="flex justify-center">
                                            <img src="{{ $filePath }}" class="thumbnail-img w-10 h-10 rounded-lg object-cover border border-gray-200 shadow-sm" onclick="openImageModal('{{ $filePath }}')">
                                        </div>
                                    @else
                                        <a href="{{ $filePath }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs hover:bg-gray-200 transition"><i class="fas fa-file-pdf"></i> PDF</a>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($krs)
                                    <a href="{{ route('dosen.krs.detail', $krs->id) }}" class="btn-detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fas fa-inbox text-4xl"></i>
                                    <p>Belum ada mahasiswa bimbingan</p>
                                    <p class="text-sm">Mahasiswa yang memilih Anda sebagai dosen PA akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal preview gambar -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-modal">&times;</span>
    <img id="modalImage" src="" alt="Preview Gambar">
</div>

<script>
    function openImageModal(imageUrl, altText = 'Preview Gambar') {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.classList.add('active');
        modalImg.src = imageUrl;
        modalImg.alt = altText;
        document.body.style.overflow = 'hidden';
    }
    function closeImageModal() {
        document.getElementById('imageModal').classList.remove('active');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', function(e) { 
        if (e.key === 'Escape') { 
            closeImageModal(); 
        } 
    });
</script>
@endsection