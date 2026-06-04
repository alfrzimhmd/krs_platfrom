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
    
    /* Modal preview gambar */
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
    
    /* Modal Detail Mahasiswa */
    .detail-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(4px);
    }
    .detail-modal.active {
        display: flex;
    }
    .detail-modal-content {
        background: white;
        border-radius: 24px;
        max-width: 700px;
        width: 90%;
        max-height: 85vh;
        overflow-y: auto;
        animation: modalFadeIn 0.3s ease-out;
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.2);
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
    .detail-modal-header {
        background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%);
        border-radius: 24px 24px 0 0;
        padding: 24px 28px;
        color: white;
    }
    .detail-modal-body {
        padding: 24px 28px;
        background: white;
        border-radius: 0 0 24px 24px;
    }
    .info-section {
        margin-bottom: 28px;
    }
    .info-section-title {
        font-size: 15px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid #E5E7EB;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info-section-title i {
        color: #0D9488;
        font-size: 18px;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    .info-card {
        background: #F8FAFC;
        border-radius: 14px;
        padding: 14px 16px;
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
        font-size: 15px;
        font-weight: 600;
        color: #1F2937;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
    }
    .matkul-table {
        width: 100%;
        border-collapse: collapse;
        background: #F8FAFC;
        border-radius: 14px;
        overflow: hidden;
    }
    .matkul-table th {
        text-align: left;
        padding: 12px 14px;
        background: #EFF3F6;
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
    }
    .matkul-table td {
        padding: 10px 14px;
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
    .empty-matkul {
        text-align: center;
        padding: 32px;
        background: #F8FAFC;
        border-radius: 14px;
    }
    .clickable-name {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .clickable-name:hover {
        color: #0D9488;
        text-decoration: underline;
    }
    .student-avatar {
        width: 52px;
        height: 52px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }
    .detail-modal-content::-webkit-scrollbar {
        width: 6px;
    }
    .detail-modal-content::-webkit-scrollbar-track {
        background: #F1F1F1;
        border-radius: 10px;
    }
    .detail-modal-content::-webkit-scrollbar-thumb {
        background: #0D9488;
        border-radius: 10px;
    }
    .action-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
    }
    .btn-icon {
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-edit {
        background: #F59E0B;
        color: white;
    }
    .btn-edit:hover {
        background: #D97706;
        transform: scale(1.02);
    }
    .btn-delete {
        background: #EF4444;
        color: white;
    }
    .btn-delete:hover {
        background: #DC2626;
        transform: scale(1.02);
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
                <div><h2 class="font-bold text-gray-800">Daftar KRS Mahasiswa Bimbingan</h2><p class="text-sm text-gray-500">Kelola, setujui, edit, atau hapus data mahasiswa</p></div>
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
                        @php $krs = $mahasiswa->krs->first(); @endphp
                        <tr class="table-row-hover" 
                            data-nama="{{ $mahasiswa->nama }}"
                            data-nim="{{ $mahasiswa->nim }}"
                            data-semester="{{ $krs ? $krs->semester : $mahasiswa->semester_saat_ini }}"
                            data-dosen="{{ $mahasiswa->dosen->nama_dosen ?? 'Belum dipilih' }}"
                            data-status="{{ $krs ? $krs->status : 'Belum mengajukan' }}"
                            data-total-sks="{{ $krs ? $krs->total_sks : 0 }}"
                            data-tanggal="{{ $krs ? $krs->created_at->translatedFormat('d F Y, H:i') : '-' }}"
                            data-matakuliah='@json($krs ? $krs->matakuliahs : [])'>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-graduate text-gray-500 text-sm"></i>
                                    </div>
                                    <span class="font-medium text-gray-800 clickable-name" onclick="showDetailModal(this)">{{ $mahasiswa->nama }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-gray-600">{{ $mahasiswa->nim }}</td>
                            <td class="px-4 py-4"><span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">{{ $krs ? $krs->semester : '-' }}</span></td>
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
                                <div class="action-buttons">
                                    @if($krs && $krs->status == 'menunggu')
                                        <form method="POST" action="{{ route('dosen.krs.approve', $krs->id) }}" class="inline" onsubmit="return confirm('Setujui pengajuan KRS ini?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs hover:bg-emerald-600 transition">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('dosen.krs.reject', $krs->id) }}" class="inline" onsubmit="return confirm('Tolak pengajuan KRS ini?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600 transition">Tolak</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('dosen.mahasiswa.edit', $mahasiswa->id) }}" class="btn-icon btn-edit inline-flex items-center gap-1"><i class="fas fa-edit"></i> Edit</a>
                                    <form method="POST" action="{{ route('dosen.mahasiswa.destroy', $mahasiswa->id) }}" class="inline" onsubmit="return confirm('Hapus mahasiswa {{ $mahasiswa->nama }}? Semua data KRS akan hilang.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete inline-flex items-center gap-1"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-6 py-12 text-center text-gray-400"><div class="flex flex-col items-center gap-3"><i class="fas fa-inbox text-4xl"></i><p>Belum ada mahasiswa bimbingan</p><p class="text-sm">Mahasiswa yang memilih Anda sebagai dosen PA akan muncul di sini</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal preview gambar -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-modal">&times;</span>
    <img id="modalImage" src="" alt="Preview Bukti UKT">
</div>

<!-- Modal Detail Mahasiswa -->
<div id="detailModal" class="detail-modal" onclick="closeDetailModalOnClick(event)">
    <div class="detail-modal-content">
        <div class="detail-modal-header">
            <div class="student-avatar"><i class="fas fa-user-graduate text-white text-2xl"></i></div>
            <h3 class="text-xl font-bold" id="detailNama">Loading...</h3>
            <p class="text-teal-100 text-sm mt-1" id="detailNim">-</p>
        </div>
        <div class="detail-modal-body">
            <!-- Informasi Akademik -->
            <div class="info-section">
                <div class="info-section-title"><i class="fas fa-graduation-cap"></i><span>Informasi Akademik</span></div>
                <div class="info-grid">
                    <div class="info-card"><span class="info-label">DOSEN PEMBIMBING AKADEMIK</span><span class="info-value" id="detailDosen">-</span></div>
                    <div class="info-card"><span class="info-label">SEMESTER</span><span class="info-value" id="detailSemester">-</span></div>
                    <div class="info-card"><span class="info-label">TANGGAL PENGAJUAN KRS</span><span class="info-value" id="detailTanggal">-</span></div>
                    <div class="info-card"><span class="info-label">STATUS PENGAJUAN</span><span class="info-value" id="detailStatus">-</span></div>
                </div>
            </div>
            <!-- Mata Kuliah -->
            <div class="info-section">
                <div class="info-section-title"><i class="fas fa-book-open"></i><span>Mata Kuliah yang Diambil</span></div>
                <div id="detailMatkul"><div class="empty-matkul"><i class="fas fa-spinner fa-pulse"></i><p>Memuat data...</p></div></div>
            </div>
            <div class="flex justify-end mt-6 pt-4 border-t border-gray-100">
                <button onclick="closeDetailModal()" class="px-5 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition font-medium"><i class="fas fa-times mr-2"></i>Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
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
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeImageModal(); closeDetailModal(); } });
    
    function showDetailModal(element) {
        const row = element.closest('tr');
        document.getElementById('detailNama').textContent = row.getAttribute('data-nama') || '-';
        document.getElementById('detailNim').textContent = 'NIM: ' + (row.getAttribute('data-nim') || '-');
        document.getElementById('detailDosen').textContent = row.getAttribute('data-dosen') || '-';
        document.getElementById('detailSemester').textContent = row.getAttribute('data-semester') || '-';
        document.getElementById('detailTanggal').textContent = row.getAttribute('data-tanggal') || '-';
        
        const status = row.getAttribute('data-status') || 'Belum mengajukan';
        const statusEl = document.getElementById('detailStatus');
        let statusClass = '', statusIcon = '', statusText = status;
        if (status.toLowerCase() === 'menunggu') { statusClass = 'status-badge bg-amber-50 text-amber-700'; statusIcon = 'fa-hourglass-half'; statusText = 'Menunggu Persetujuan'; }
        else if (status.toLowerCase() === 'disetujui') { statusClass = 'status-badge bg-emerald-50 text-emerald-700'; statusIcon = 'fa-check-circle'; statusText = 'Disetujui'; }
        else if (status.toLowerCase() === 'ditolak') { statusClass = 'status-badge bg-red-50 text-red-700'; statusIcon = 'fa-times-circle'; statusText = 'Ditolak'; }
        else { statusClass = 'status-badge bg-gray-100 text-gray-600'; statusIcon = 'fa-clock'; statusText = 'Belum Mengajukan KRS'; }
        statusEl.innerHTML = `<span class="${statusClass}"><i class="fas ${statusIcon}"></i> ${statusText}</span>`;
        
        let matakuliah = [];
        try { matakuliah = JSON.parse(row.getAttribute('data-matakuliah') || '[]'); } catch(e) {}
        const container = document.getElementById('detailMatkul');
        if (matakuliah.length > 0) {
            let total = 0;
            let html = `<table class="matkul-table"><thead><tr><th>Kode MK</th><th>Nama Mata Kuliah</th><th style="text-align:center">SKS</th></tr></thead><tbody>`;
            matakuliah.forEach(mk => { total += (mk.sks || 0); html += `<tr><td>${mk.kode_mk || '-'}</td><td>${mk.nama_mk || '-'}</td><td style="text-align:center"><span class="font-semibold text-teal-600">${mk.sks || 0}</span> SKS</td></tr>`; });
            html += `<tr class="total-row"><td colspan="2" style="text-align:right; font-weight:700;">Total SKS</td><td style="text-align:center"><span class="font-bold text-teal-600">${total}</span> SKS</td></tr></tbody></table>`;
            container.innerHTML = html;
        } else {
            container.innerHTML = `<div class="empty-matkul"><i class="fas fa-inbox"></i><p>Belum ada mata kuliah yang dipilih</p></div>`;
        }
        document.getElementById('detailModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeDetailModal() {
        document.getElementById('detailModal').classList.remove('active');
        document.body.style.overflow = '';
    }
    function closeDetailModalOnClick(event) { if (event.target === event.currentTarget) closeDetailModal(); }
</script>
@endsection