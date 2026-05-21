@extends('layouts.app')

@section('title', 'Daftar Pengajuan KRS')

@section('content')
<div class="space-y-4 sm:space-y-6">
    
    <!-- Header Section - Responsive -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-white">
                <i class="fas fa-table-list text-white/80 mr-2"></i> Daftar Pengajuan KRS
            </h2>
            <p class="text-white/70 text-sm sm:text-base mt-1">Mengelola data pengajuan rencana studi mahasiswa</p>
        </div>
        <a href="{{ route('krs.create') }}" class="bg-white text-indigo-600 hover:bg-indigo-50 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-sm sm:text-base w-full sm:w-auto justify-center">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Pengajuan
        </a>
    </div>

    <!-- Flash Messages - Responsive -->
    @if(session('success'))
        <div class="bg-green-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-between animate-fadeInUp">
            <div class="flex items-center text-sm sm:text-base">
                <i class="fas fa-check-circle mr-2 sm:mr-3 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2 sm:mr-3 text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Card Table - Mobile Friendly -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <!-- Search Bar - Responsive -->
        <div class="p-3 sm:p-4 md:p-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col sm:flex-row gap-3 justify-between items-center">
                <div class="relative w-full sm:w-72 md:w-80">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama atau NIM..." 
                           class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
                <div class="text-xs sm:text-sm text-gray-500 flex items-center gap-2">
                    <i class="fas fa-database"></i>
                    <span>Total Data: <span class="font-semibold text-indigo-600">{{ $submissions->count() }}</span></span>
                </div>
            </div>
        </div>

        <!-- Mobile View (Card Layout untuk HP) -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($submissions as $submission)
            <div class="p-4 hover:bg-gray-50 transition-all duration-200">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-graduate text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $submission->student_name }}</h3>
                            <p class="text-xs text-gray-500">{{ $submission->nim }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-1">
                        <a href="{{ route('krs.edit', $submission->id) }}" class="p-2 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                            <i class="fas fa-edit text-indigo-600 text-sm"></i>
                        </a>
                        <form action="{{ route('krs.destroy', $submission->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                <i class="fas fa-trash-alt text-red-600 text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <p class="text-xs text-gray-500">Semester</p>
                        <p class="font-medium">Semester {{ $submission->semester }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Total SKS</p>
                        <p class="font-medium">{{ $submission->total_credits }} SKS</p>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-xs text-gray-500">Mata Kuliah</p>
                    <p class="text-sm text-gray-700 truncate">{{ Str::limit($submission->courses_list, 50) }}</p>
                </div>
                <div class="mt-3">
                    @if($submission->status == 'pending')
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-700">
                            <i class="fas fa-clock mr-1"></i> Pending
                        </span>
                    @elseif($submission->status == 'approved')
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-700">
                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-700">
                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                        </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Belum ada data pengajuan KRS</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop View (Table Layout) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">SKS</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($submissions as $index => $submission)
                    <tr class="hover:bg-gray-50 transition-all duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-9 w-9 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-graduate text-white text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $submission->student_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $submission->nim }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-700">
                                <i class="fas fa-layer-group mr-1 text-xs"></i> {{ $submission->semester }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                            <div class="truncate" title="{{ $submission->courses_list }}">
                                {{ Str::limit($submission->courses_list, 40) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-700">
                                <i class="fas fa-book mr-1 text-xs"></i> {{ $submission->total_credits }} SKS
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($submission->status == 'pending')
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-700">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @elseif($submission->status == 'approved')
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i> Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-700">
                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('krs.edit', $submission->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-all duration-200">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('krs.destroy', $submission->id) }}" method="POST" class="delete-form inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-all duration-200">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                                <p class="text-gray-500">Belum ada data pengajuan KRS</p>
                                <a href="{{ route('krs.create') }}" class="mt-3 text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah data pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Footer Info -->
        <div class="px-4 sm:px-6 py-3 border-t border-gray-200 bg-gray-50 text-[10px] sm:text-xs text-gray-500 flex flex-col sm:flex-row justify-between gap-2">
            <span><i class="fas fa-info-circle mr-1"></i> Klik ikon edit untuk mengubah data</span>
            <span><i class="fas fa-trash-alt mr-1"></i> Klik ikon hapus untuk menghapus data</span>
        </div>
    </div>
</div>

<!-- SweetAlert2 dan Search Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi hapus dengan SweetAlert
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, hapus!',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',
                background: '#fff',
                customClass: {
                    popup: 'rounded-2xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Fitur search
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        
        // Untuk mobile (card view)
        let mobileCards = document.querySelectorAll('.block.md\\:hidden .divide-y > div');
        mobileCards.forEach(card => {
            let text = card.textContent.toLowerCase();
            card.style.display = text.includes(filter) ? '' : 'none';
        });
        
        // Untuk desktop (table view)
        let desktopRows = document.querySelectorAll('.hidden.md\\:block tbody tr');
        desktopRows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection