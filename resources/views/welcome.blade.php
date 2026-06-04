@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center text-center py-12">
    <div class="mb-8">
        <div class="w-24 h-24 bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
            <i class="fas fa-graduation-cap text-white text-4xl"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
            Sistem Kartu Rencana Studi
        </h1>
        <p class="text-lg text-gray-500 max-w-lg mx-auto">
            Platform digital untuk pengajuan KRS online. Mudah, cepat, dan terintegrasi.
        </p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-4 mt-8">
        <a href="{{ route('mahasiswa.login.form') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-xl font-semibold transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
            <i class="fas fa-user-graduate"></i>
            Akses Mahasiswa
        </a>
        <a href="{{ route('login') }}" class="bg-white hover:bg-gray-50 text-teal-600 border-2 border-teal-600 px-8 py-3 rounded-xl font-semibold transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
            <i class="fas fa-chalkboard-user"></i>
            Portal Dosen
        </a>
    </div>
    
    <!-- Feature Cards -->
    <div class="grid md:grid-cols-3 gap-6 mt-20 w-full max-w-5xl">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-check-circle text-teal-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 mb-2">Pengajuan Mudah</h3>
            <p class="text-gray-500 text-sm">Pilih mata kuliah yang diinginkan dan ajukan KRS secara online.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-chart-line text-teal-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 mb-2">Real-time Status</h3>
            <p class="text-gray-500 text-sm">Pantau status persetujuan KRS Anda secara langsung.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-shield-alt text-teal-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 mb-2">Terintegrasi</h3>
            <p class="text-gray-500 text-sm">Terhubung dengan sistem akademik dan dosen pembimbing.</p>
        </div>
    </div>
</div>
@endsection