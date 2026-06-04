@extends('layouts.app')

@section('title', 'Dashboard KRS')

@section('content')
<style>
    .thumbnail-preview {
        transition: all 0.2s ease;
    }
    .thumbnail-preview:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .status-badge {
        position: relative;
        overflow: hidden;
    }
    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shimmer 2s infinite;
    }
    @keyframes shimmer {
        100% { left: 100%; }
    }
</style>

<div class="max-w-6xl mx-auto">
    <!-- Header dengan logout button -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-user-graduate text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard KRS</h1>
                <p class="text-gray-500">Semester {{ $semester }} - {{ date('Y') }}</p>
            </div>
        </div>
        
        <!-- Tombol Logout -->
        <form method="POST" action="{{ route('mahasiswa.logout') }}" id="logout-form-mahasiswa">
            @csrf
            <button type="button" onclick="confirmLogoutMahasiswa()" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition shadow-sm">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
    
    <!-- Info Mahasiswa Card - Premium -->
    <div class="relative overflow-hidden bg-gradient-to-r from-teal-600 to-teal-500 rounded-2xl mb-8 text-white shadow-xl">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        <div class="relative p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-inner">
                        <i class="fas fa-id-card text-white text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-teal-100 text-sm">Selamat Datang,</p>
                        <p class="text-xl font-bold">{{ $mahasiswa->nama }}</p>
                        <p class="text-teal-100 text-sm">NIM: {{ $mahasiswa->nim }}</p>
                    </div>
                </div>
                <div class="px-5 py-3 bg-white/20 rounded-xl backdrop-blur-sm text-center">
                    <p class="text-xs text-teal-100">Semester Akademik</p>
                    <p class="font-bold text-xl">{{ $semester }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status KRS Card (Jika sudah ada KRS dan status bukan menunggu) -->
    @if($krs && $krs->status != 'menunggu')
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-teal-500"></i>
                Status Pengajuan KRS Terakhir
            </h2>
        </div>
        <div class="p-6">
            @php
                $statusConfig = [
                    'disetujui' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => 'fa-check-circle', 'label' => 'DISETUJUI'],
                    'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'fa-times-circle', 'label' => 'DITOLAK']
                ];
                $config = $statusConfig[$krs->status];
            @endphp
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 {{ $config['bg'] }} rounded-full flex items-center justify-center">
                        <i class="fas {{ $config['icon'] }} {{ $config['text'] }} text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Status Pengajuan</p>
                        <p class="font-bold text-xl {{ $config['text'] }}">{{ $config['label'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 text-sm">Total SKS</p>
                    <p class="font-bold text-gray-800 text-2xl">{{ $krs->total_sks }} <span class="text-gray-400 text-sm">SKS</span></p>
                </div>
            </div>
            
            <!-- Daftar Mata Kuliah -->
            <div class="mt-5 pt-4 border-t border-gray-100">
                <p class="text-sm font-medium text-gray-700 mb-3">Mata Kuliah yang diajukan:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($krs->matakuliahs as $mk)
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm flex items-center gap-1">
                            <i class="fas fa-book text-teal-500 text-xs"></i>
                            {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                        </span>
                    @endforeach
                </div>
            </div>
            
            @if($krs->bukti_ukt_path)
            <div class="mt-4 pt-3 border-t border-gray-100">
                <a href="{{ Storage::url($krs->bukti_ukt_path) }}" target="_blank" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 text-sm font-medium">
                    <i class="fas fa-file-pdf"></i> Lihat Bukti Pembayaran UKT
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Form KRS Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-teal-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-edit text-teal-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-800 text-lg">
                        @if($krs && $krs->status == 'menunggu')
                            Edit Pengajuan KRS
                        @else
                            Form Pengajuan KRS
                        @endif
                    </h2>
                    <p class="text-sm text-gray-500">Isi form berikut untuk mengajukan KRS</p>
                </div>
            </div>
        </div>
        
        @if($krs && $krs->status == 'disetujui')
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-emerald-500 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pengajuan KRS Telah Disetujui</h3>
                <p class="text-gray-500">Anda tidak dapat mengajukan atau mengubah KRS karena sudah disetujui oleh dosen PA.</p>
            </div>
        @elseif($krs && $krs->status == 'ditolak')
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times-circle text-red-500 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pengajuan KRS Ditolak</h3>
                <p class="text-gray-500 mb-4">Pengajuan KRS Anda ditolak oleh dosen PA. Silakan hubungi dosen PA untuk informasi lebih lanjut.</p>
                @if($krs->isEditable())
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn-primary px-6 py-2.5 rounded-xl text-white inline-flex items-center gap-2">
                        <i class="fas fa-redo"></i> Ajukan Ulang
                    </a>
                @endif
            </div>
        @else
        <form method="POST" action="{{ route('mahasiswa.store') }}" enctype="multipart/form-data" id="krsForm">
            @csrf
            
            <!-- Pilih Dosen PA -->
            <div class="p-6 border-b border-gray-100">
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-chalkboard-user text-teal-500 mr-2"></i>
                    Dosen Pembimbing Akademik (PA)
                </label>
                <select name="dosen_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition" required>
                    <option value="">-- Pilih Dosen PA --</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ ($mahasiswa->dosen_id == $dosen->id || ($existingKrs && $existingKrs->mahasiswa->dosen_id == $dosen->id)) ? 'selected' : '' }}>
                            👨‍🏫 {{ $dosen->nama_dosen }} (NIDN: {{ $dosen->nidn }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Upload Bukti UKT dengan Thumbnail Preview -->
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
                
                <!-- Thumbnail Preview -->
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
            
            <!-- Daftar Mata Kuliah -->
            <div class="p-6 border-b border-gray-100">
                <label class="block text-gray-700 font-semibold mb-3">
                    <i class="fas fa-book-open text-teal-500 mr-2"></i>
                    Pilih Mata Kuliah <span class="text-sm text-gray-400 font-normal">(Semester {{ $semester }})</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-1">
                    @forelse($matakuliahs as $mk)
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
                    @empty
                        <div class="col-span-2 text-center py-8 text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Belum ada mata kuliah untuk semester {{ $semester }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Ringkasan Premium -->
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
            
            <!-- Action Buttons -->
            <div class="p-6 flex justify-end gap-3 bg-white">
                <button type="submit" class="btn-primary px-8 py-3 rounded-xl font-semibold text-white shadow-md flex items-center gap-2 transition-all">
                    <i class="fas fa-paper-plane"></i>
                    {{ $existingKrs ? 'Update KRS' : 'Kirim KRS' }}
                </button>
            </div>
        </form>
        @endif
    </div>
</div>

@push('scripts')
<script>
    let currentFile = null;
    
    // File upload with thumbnail preview
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
            reader.onload = function(e) {
                thumbnailImage.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            };
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
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-teal-500', 'bg-teal-50');
        });
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-teal-500', 'bg-teal-50');
        });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-teal-500', 'bg-teal-50');
            const files = e.dataTransfer.files;
            if (files.length) {
                fileInput.files = files;
                previewFile(files[0]);
                fileName.textContent = `📄 ${files[0].name}`;
                fileName.classList.remove('hidden');
            }
        });
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                previewFile(e.target.files[0]);
                fileName.textContent = `📄 ${e.target.files[0].name}`;
                fileName.classList.remove('hidden');
            }
        });
    }
    
    // Update ringkasan mata kuliah
    function updateRingkasan() {
        let selected = [];
        let total = 0;
        
        document.querySelectorAll('.mk-checkbox:checked').forEach(cb => {
            let name = cb.dataset.name;
            let sks = parseInt(cb.dataset.sks);
            selected.push({ name, sks });
            total += sks;
        });
        
        const listContainer = document.getElementById('selected-list');
        const totalSpan = document.getElementById('total-sks');
        
        if (selected.length) {
            let html = '<div class="space-y-2">';
            selected.forEach((item, index) => {
                html += `<div class="flex justify-between items-center py-1 border-b border-gray-100 last:border-0">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-teal-500 text-xs"></i>
                                <span class="text-gray-700">${item.name}</span>
                            </div>
                            <span class="text-gray-500 text-sm font-medium">${item.sks} SKS</span>
                         </div>`;
            });
            html += '</div>';
            listContainer.innerHTML = html;
        } else {
            listContainer.innerHTML = '<p class="text-gray-400 italic flex items-center gap-2"><i class="fas fa-info-circle"></i> Belum ada mata kuliah dipilih</p>';
        }
        
        totalSpan.textContent = total;
    }
    
    document.querySelectorAll('.mk-checkbox').forEach(cb => {
        cb.addEventListener('change', updateRingkasan);
    });
    
    updateRingkasan();
    
    // Confirm logout function
    function confirmLogoutMahasiswa() {
        if (confirm('Apakah Anda yakin ingin logout dari akun mahasiswa?')) {
            document.getElementById('logout-form-mahasiswa').submit();
        }
    }
</script>
@endpush
@endsection