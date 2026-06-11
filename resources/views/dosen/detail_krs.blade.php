@extends('layouts.app')

@section('title', 'Detail KRS Mahasiswa')

@section('content')
<style>
    .info-card {
        background: #F8FAFC;
        border-radius: 16px;
        padding: 16px 20px;
        transition: all 0.2s ease;
    }
    .info-card:hover {
        background: #F1F5F9;
    }
    .info-label {
        font-size: 11px;
        font-weight: 600;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 6px;
    }
    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: #1F2937;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
    }
    .matkul-table {
        width: 100%;
        border-collapse: collapse;
        background: #F8FAFC;
        border-radius: 16px;
        overflow: hidden;
    }
    .matkul-table th {
        text-align: left;
        padding: 14px 16px;
        background: #EFF3F6;
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
    }
    .matkul-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #E5E7EB;
        font-size: 14px;
        color: #374151;
        background: white;
    }
    .matkul-table tr:last-child td {
        border-bottom: none;
    }
    .total-row td {
        background: #F0FDFA;
        font-weight: 700;
        color: #0D9488;
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
    
    /* Modal Edit */
    .edit-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 10001;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(4px);
    }
    .edit-modal.active {
        display: flex;
    }
    .edit-modal-content {
        background: white;
        border-radius: 24px;
        max-width: 500px;
        width: 90%;
        animation: modalFadeIn 0.3s ease-out;
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.2);
    }
    .edit-modal-header {
        background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%);
        border-radius: 24px 24px 0 0;
        padding: 20px 24px;
        color: white;
    }
    .edit-modal-body {
        padding: 24px;
    }
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.96) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    .btn-primary {
        background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%);
        transition: all 0.2s ease;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #0F766E 0%, #0D9488 100%);
        transform: scale(1.02);
        box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
    }
</style>

<div class="max-w-4xl mx-auto">
    <!-- Header Card dengan Nama Mahasiswa (tanpa tombol back di atas) -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-6 py-5">
            <div class="flex items-center gap-4">
                @if($krs->mahasiswa->foto_profil)
                    <img src="{{ Storage::url($krs->mahasiswa->foto_profil) }}" class="w-14 h-14 rounded-xl object-cover shadow-lg ring-2 ring-white/30">
                @else
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-user-graduate text-white text-2xl"></i>
                    </div>
                @endif
                <div>
                    <p class="text-teal-100 text-sm">Detail KRS Mahasiswa</p>
                    <h2 class="text-xl font-bold text-white">{{ $krs->mahasiswa->nama }}</h2>
                    <p class="text-teal-100 text-sm">NIM: {{ $krs->mahasiswa->nim }} | Email: {{ $krs->mahasiswa->email }}</p>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Informasi Akademik -->
            <div>
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
                    <i class="fas fa-graduation-cap text-teal-500"></i>
                    Informasi Akademik
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="info-card">
                        <span class="info-label">DOSEN PEMBIMBING AKADEMIK</span>
                        <span class="info-value">{{ $krs->mahasiswa->dosen->nama_dosen ?? 'Belum dipilih' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-label">SEMESTER</span>
                        <span class="info-value">{{ $krs->semester }} / {{ $krs->mahasiswa->nomor_semester ?? '-' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-label">TANGGAL PENGAJUAN</span>
                        <span class="info-value">{{ $krs->created_at->translatedFormat('d F Y, H:i') }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-label">STATUS PENGAJUAN</span>
                        <span class="info-value">
                            @php
                                $statusClass = $krs->status == 'menunggu' ? 'bg-amber-100 text-amber-700' : ($krs->status == 'disetujui' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700');
                                $statusIcon = $krs->status == 'menunggu' ? 'fa-hourglass-half' : ($krs->status == 'disetujui' ? 'fa-check-circle' : 'fa-times-circle');
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }}"></i>
                                {{ ucfirst($krs->status) }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Mata Kuliah yang Dipilih -->
            <div>
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
                    <i class="fas fa-book-open text-teal-500"></i>
                    Mata Kuliah yang Dipilih
                </h3>
                <div class="overflow-x-auto">
                    <table class="matkul-table">
                        <thead>
                            <tr>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th style="text-align: center">SKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalSks = 0; @endphp
                            @foreach($krs->matakuliahs as $mk)
                                @php $totalSks += $mk->sks; @endphp
                                <tr>
                                    <td><span class="font-mono text-sm">{{ $mk->kode_mk }}</span></td>
                                    <td>{{ $mk->nama_mk }}</td>
                                    <td style="text-align: center"><span class="font-semibold text-teal-600">{{ $mk->sks }}</span> SKS</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="2" style="text-align: right; font-weight: 700;">Total SKS</td>
                                <td style="text-align: center"><span class="font-bold text-teal-600">{{ $totalSks }}</span> SKS</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bukti UKT -->
            @if($krs->bukti_ukt_path)
            <div>
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
                    <i class="fas fa-receipt text-teal-500"></i>
                    Bukti Pembayaran UKT
                </h3>
                @php
                    $filePath = Storage::url($krs->bukti_ukt_path);
                    $isImage = in_array(pathinfo($krs->bukti_ukt_path, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif','webp']);
                @endphp
                @if($isImage)
                    <div class="flex justify-center">
                        <img src="{{ $filePath }}" class="thumbnail-img w-48 h-48 rounded-xl object-cover border border-gray-200 shadow-md" onclick="openImageModal('{{ $filePath }}')">
                    </div>
                    <p class="text-center text-sm text-gray-500 mt-2">Klik gambar untuk memperbesar</p>
                @else
                    <a href="{{ $filePath }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 text-teal-600 rounded-xl hover:bg-teal-100 transition">
                        <i class="fas fa-file-pdf"></i> Lihat File PDF
                    </a>
                @endif
            </div>
            @endif

            <!-- Tombol Aksi -->
            <div class="pt-4 border-t border-gray-200">
                @if($krs->status == 'menunggu')
                    <div class="flex flex-wrap justify-end gap-3">
                        <button onclick="openEditModal()" class="px-6 py-2.5 bg-amber-500 text-white rounded-xl font-semibold hover:bg-amber-600 transition shadow-md flex items-center gap-2">
                            <i class="fas fa-edit"></i> Edit Data Mahasiswa
                        </button>
                        <form method="POST" action="{{ route('dosen.krs.approve', $krs->id) }}" class="inline" onsubmit="return confirm('Setujui pengajuan KRS ini?')">
                            @csrf
                            <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white rounded-xl font-semibold hover:bg-emerald-600 transition shadow-md flex items-center gap-2">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                        </form>
                        <form method="POST" action="{{ route('dosen.krs.reject', $krs->id) }}" class="inline" onsubmit="return confirm('Tolak pengajuan KRS ini?')">
                            @csrf
                            <button type="submit" class="px-6 py-2.5 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition shadow-md flex items-center gap-2">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex justify-end">
                        <button onclick="openEditModal()" class="px-6 py-2.5 bg-amber-500 text-white rounded-xl font-semibold hover:bg-amber-600 transition shadow-md flex items-center gap-2">
                            <i class="fas fa-edit"></i> Edit Data Mahasiswa & Status
                        </button>
                    </div>
                @endif
            </div>
            
            <!-- Tombol Kembali ke Dashboard (di bawah) -->
            <div class="pt-4 flex justify-center">
                <a href="{{ route('dosen.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Data Mahasiswa -->
<div id="editModal" class="edit-modal" onclick="closeEditModalOnClick(event)">
    <div class="edit-modal-content">
        <div class="edit-modal-header">
            <div class="flex items-center gap-2">
                <i class="fas fa-edit text-white"></i>
                <h3 class="text-lg font-bold">Edit Data Mahasiswa</h3>
            </div>
            <p class="text-teal-100 text-sm mt-1">Perbaharui informasi mahasiswa</p>
        </div>
        <div class="edit-modal-body">
            <form method="POST" action="{{ route('dosen.mahasiswa.update', $krs->mahasiswa->id) }}" id="editForm">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="krs_id" value="{{ $krs->id }}">
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">
                        <i class="fas fa-user text-teal-500 mr-2"></i>Nama Lengkap
                    </label>
                    <input type="text" name="nama" value="{{ $krs->mahasiswa->nama }}" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">
                        <i class="fas fa-id-card text-teal-500 mr-2"></i>NIM
                    </label>
                    <input type="text" name="nim" value="{{ $krs->mahasiswa->nim }}" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition" required>
                </div>
                
                @if($krs->status != 'menunggu')
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2 text-sm">
                        <i class="fas fa-tag text-teal-500 mr-2"></i>Status KRS
                    </label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition">
                        <option value="menunggu" {{ $krs->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ $krs->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ $krs->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                @endif
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 btn-primary rounded-xl text-white font-semibold shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal preview gambar -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-modal">&times;</span>
    <img id="modalImage" src="" alt="Preview Bukti UKT">
</div>

<script>
    // Image Modal
    function openImageModal(imageUrl) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.classList.add('active');
        modalImg.src = imageUrl;
        document.body.style.overflow = 'hidden';
    }
    function closeImageModal() {
        document.getElementById('imageModal').classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Edit Modal
    function openEditModal() {
        document.getElementById('editModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        document.body.style.overflow = '';
    }
    function closeEditModalOnClick(event) {
        if (event.target === event.currentTarget) {
            closeEditModal();
        }
    }
    
    // ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
            closeEditModal();
        }
    });
</script>
@endsection