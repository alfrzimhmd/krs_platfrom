@extends('layouts.app')

@section('title', 'Form Pengisian KRS')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-alt text-teal-600 text-lg"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Form Pengisian KRS</h1>
        </div>
        <p class="text-gray-500 ml-14">Semester {{ $semester }} - {{ date('Y') }}</p>
    </div>
    
    <!-- Student Info Card -->
    <div class="bg-gradient-to-r from-teal-50 to-teal-100/50 rounded-2xl p-5 mb-8 border border-teal-100 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-user-graduate text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Mahasiswa</p>
                    <p class="font-bold text-gray-800">{{ session('mahasiswa.nama') }}</p>
                    <p class="text-sm text-gray-500">NIM: {{ session('mahasiswa.nim') }}</p>
                </div>
            </div>
            <div class="px-4 py-2 bg-white/60 rounded-xl">
                <p class="text-xs text-gray-500">Semester Akademik</p>
                <p class="font-semibold text-teal-600">{{ $semester }}</p>
            </div>
        </div>
    </div>
    
    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <form method="POST" action="{{ route('mahasiswa.krs.store') }}" enctype="multipart/form-data" id="krsForm">
            @csrf
            
            <!-- Pilih Dosen PA -->
            <div class="p-6 border-b border-gray-100">
                <label class="block text-gray-700 font-bold mb-3">
                    <i class="fas fa-chalkboard-user text-teal-500 mr-2"></i>
                    Dosen Pembimbing Akademik (PA)
                </label>
                <select name="dosen_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition" required>
                    <option value="">-- Pilih Dosen PA --</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ $mahasiswa->dosen_id == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->nama_dosen }} (NIDN: {{ $dosen->nidn }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Upload Bukti UKT -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                <label class="block text-gray-700 font-bold mb-3">
                    <i class="fas fa-upload text-teal-500 mr-2"></i>
                    Bukti Pembayaran UKT
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-teal-400 transition cursor-pointer" id="uploadArea">
                    <input type="file" name="bukti_ukt" id="fileInput" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Klik atau drag file ke sini</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF (Max 2MB)</p>
                    <div id="fileName" class="mt-2 text-sm text-teal-600 hidden"></div>
                </div>
                @error('bukti_ukt')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Daftar Mata Kuliah -->
            <div class="p-6 border-b border-gray-100">
                <label class="block text-gray-700 font-bold mb-4">
                    <i class="fas fa-book-open text-teal-500 mr-2"></i>
                    Pilih Mata Kuliah
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-2">
                    @foreach($matakuliahs as $mk)
                        <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:border-teal-300 hover:bg-teal-50/30 transition cursor-pointer">
                            <input type="checkbox" name="matakuliah_ids[]" value="{{ $mk->id }}"
                                {{ in_array($mk->id, $selectedMatakuliahs) ? 'checked' : '' }}
                                class="mt-0.5 w-4 h-4 text-teal-600 rounded focus:ring-teal-500 mk-checkbox"
                                data-sks="{{ $mk->sks }}" data-name="{{ $mk->kode_mk }} - {{ $mk->nama_mk }}">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800 text-sm">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</p>
                                <p class="text-xs text-gray-400">{{ $mk->sks }} SKS</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @if($matakuliahs->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>Belum ada mata kuliah untuk semester {{ $semester }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Ringkasan -->
            <div class="p-6 bg-gradient-to-r from-teal-50 to-teal-100/30">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-receipt text-teal-600"></i>
                    Ringkasan Pilihan
                </h3>
                <div id="selected-list" class="mb-3 text-sm text-gray-600 min-h-[60px]">
                    <p class="text-gray-400 italic">Belum ada mata kuliah dipilih</p>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-teal-200">
                    <span class="font-bold text-gray-700">Total SKS</span>
                    <span class="text-2xl font-bold text-teal-600" id="total-sks">0</span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="p-6 flex justify-end gap-3 bg-white">
                <a href="{{ route('mahasiswa.krs.status') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl font-semibold text-white shadow-md flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i>
                    Kirim KRS
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // File upload preview
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    
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
            showFileName(files[0].name);
        }
    });
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            showFileName(e.target.files[0].name);
        }
    });
    
    function showFileName(name) {
        fileName.textContent = `📄 ${name}`;
        fileName.classList.remove('hidden');
    }
    
    // Update ringkasan
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
            let html = '<div class="space-y-1">';
            selected.forEach(item => {
                html += `<div class="flex justify-between items-center text-sm">
                            <span>📘 ${item.name}</span>
                            <span class="text-gray-500">${item.sks} SKS</span>
                         </div>`;
            });
            html += '</div>';
            listContainer.innerHTML = html;
        } else {
            listContainer.innerHTML = '<p class="text-gray-400 italic">Belum ada mata kuliah dipilih</p>';
        }
        
        totalSpan.textContent = total;
    }
    
    document.querySelectorAll('.mk-checkbox').forEach(cb => {
        cb.addEventListener('change', updateRingkasan);
    });
    
    updateRingkasan();
</script>
@endpush
@endsection