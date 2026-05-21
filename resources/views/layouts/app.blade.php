<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pengajuan KRS')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.4s ease-out;
        }
        
        /* Mobile menu transition */
        .mobile-menu-enter {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-enter-active {
            max-height: 300px;
            opacity: 1;
        }
        
        /* Card hover effect */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Button ripple effect */
        .btn-ripple {
            position: relative;
            overflow: hidden;
        }
        
        .btn-ripple:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }
        
        .btn-ripple:active:after {
            width: 100%;
            height: 100%;
            padding-top: 100%;
        }
        
        /* Glassmorphism effect for cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
        }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased">
    
    <!-- Mobile Navigation Toggle Script -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                setTimeout(() => {
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                }, 10);
            } else {
                menu.style.maxHeight = '0';
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 300);
            }
        }
    </script>

    <!-- Navbar Modern dengan Mobile Support -->
    <nav class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 md:py-4">
                
                <!-- Logo & Brand - Mobile Friendly -->
                <div class="flex items-center space-x-2 md:space-x-3">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-1.5 md:p-2 rounded-xl shadow-md">
                        <i class="fas fa-graduation-cap text-white text-sm md:text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-sm md:text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Sistem KRS
                        </h1>
                        <p class="text-[10px] md:text-xs text-gray-500 hidden sm:block">Pengajuan Rencana Studi</p>
                    </div>
                </div>
                
                <!-- Desktop Navigation (Hidden on Mobile) -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('krs.index') }}" class="px-4 py-2 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200 {{ request()->routeIs('krs.index') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                        <i class="fas fa-table-list mr-2"></i> Daftar KRS
                    </a>
                    <a href="{{ route('krs.create') }}" class="px-4 py-2 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200 {{ request()->routeIs('krs.create') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Baru
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <i id="menu-icon" class="fas fa-bars text-gray-600 text-xl"></i>
                </button>
            </div>
            
            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden md:hidden overflow-hidden transition-all duration-300" style="max-height: 0;">
                <div class="py-3 space-y-2 border-t border-gray-100">
                    <a href="{{ route('krs.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200 {{ request()->routeIs('krs.index') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                        <i class="fas fa-table-list w-5 mr-3"></i>
                        <span>Daftar KRS</span>
                    </a>
                    <a href="{{ route('krs.create') }}" class="flex items-center px-3 py-2.5 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200 {{ request()->routeIs('krs.create') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                        <i class="fas fa-plus-circle w-5 mr-3"></i>
                        <span>Tambah Baru</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content dengan Padding yang Tepat untuk Mobile -->
    <main class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 md:py-8">
        <div class="animate-fadeInUp">
            @yield('content')
        </div>
    </main>

    <!-- Modern Footer -->
    <footer class="mt-auto py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="text-white/80 text-xs md:text-sm text-center sm:text-left">
                    <i class="fas fa-code mr-1"></i> Sistem Pengajuan KRS | Praktikum Pemrograman Web
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-white/60 hover:text-white transition-colors text-xs md:text-sm">
                        <i class="fab fa-github mr-1"></i> GitHub
                    </a>
                    <a href="#" class="text-white/60 hover:text-white transition-colors text-xs md:text-sm">
                        <i class="fas fa-envelope mr-1"></i> Kontak
                    </a>
                </div>
            </div>
            <div class="text-center text-white/40 text-[10px] md:text-xs mt-3">
                &copy; {{ date('Y') }} Sistem Pengajuan KRS - All rights reserved
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>