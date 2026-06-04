<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SISKRS+') - Platform KRS Digital</title>
    
    <!-- Tailwind CSS + Font Awesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        :root {
            --primary: #0D9488;
            --primary-dark: #0F766E;
            --primary-light: #14B8A6;
            --primary-soft: #CCFBF1;
            --primary-bg: #F0FDFA;
            --secondary: #F59E0B;
            --secondary-dark: #D97706;
            --success: #10B981;
            --danger: #EF4444;
            --danger-dark: #DC2626;
            --dark: #1F2937;
            --gray: #6B7280;
            --gray-light: #9CA3AF;
        }
        
        body {
            background: linear-gradient(135deg, #F0FDFA 0%, #E6F7F5 50%, #F4F9FA 100%);
            min-height: 100vh;
        }
        
        /* Sidebar Premium Styles */
        .sidebar {
            background: linear-gradient(180deg, #0F172A 0%, #1E293B 50%, #0F172A 100%);
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            margin: 4px 0;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(13, 148, 136, 0.15), transparent);
            transition: width 0.3s ease;
        }
        
        .sidebar-link:hover::before {
            width: 100%;
        }
        
        .sidebar-link:hover {
            background-color: rgba(13, 148, 136, 0.1);
            border-left-color: var(--primary-light);
            transform: translateX(4px);
        }
        
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(13, 148, 136, 0.2) 0%, transparent 100%);
            border-left-color: var(--primary);
            color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .sidebar-link.active i {
            color: var(--primary-light);
        }
        
        /* Glassmorphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(13, 148, 136, 0.1);
        }
        
        /* Button gradient */
        .btn-primary {
            background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%);
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0F766E 0%, #0D9488 100%);
            transform: scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
        }
        
        .btn-outline {
            border: 1px solid #E5E7EB;
            background: white;
            transition: all 0.2s ease;
        }
        .btn-outline:hover {
            border-color: #0D9488;
            background: #F0FDFA;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #E6F7F5;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #0D9488;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #0F766E;
        }
        
        /* Input focus effect */
        .input-focus:focus {
            outline: none;
            border-color: #0D9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }
        
        /* Animation */
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
        .animate-fade-up {
            animation: fadeInUp 0.5s ease-out;
        }
        
        /* Mobile sidebar toggle */
        .sidebar-mobile {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .sidebar-mobile.open {
            transform: translateX(0);
        }
        
        /* Avatar gradient */
        .avatar-gradient {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }
    </style>
    
    @stack('styles')
</head>
<body>
    
    @php
        $currentRouteName = Route::currentRouteName();
        $isMahasiswaDashboard = $currentRouteName === 'mahasiswa.dashboard';
        $isDosenRoute = (str_starts_with($currentRouteName, 'dosen.') || str_starts_with($currentRouteName, 'matakuliah.')) && Auth::check() && Auth::user()->role === 'dosen';
    @endphp
    
    <!-- LAYOUT DENGAN SIDEBAR (UNTUK DOSEN) -->
    @if($isDosenRoute)
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Desktop -->
        <aside class="sidebar w-72 flex-shrink-0 hidden md:block shadow-2xl overflow-y-auto">
            <div class="p-6 border-b border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="absolute inset-0 bg-teal-400 rounded-xl blur-md opacity-60"></div>
                        <div class="relative w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-graduation-cap text-white text-xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">SISKRS<span class="text-teal-400">+</span></h1>
                        <p class="text-xs text-gray-500">Dosen Panel</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="p-4 space-y-1.5">
                <a href="{{ route('dosen.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-gray-300 rounded-xl transition {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line w-5 text-lg"></i>
                    <span class="font-medium">Dashboard</span>
                    @if(request()->routeIs('dosen.dashboard'))
                        <i class="fas fa-chevron-right ml-auto text-xs text-teal-400"></i>
                    @endif
                </a>
                <a href="{{ route('matakuliah.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-gray-300 rounded-xl transition {{ request()->routeIs('matakuliah.*') ? 'active' : '' }}">
                    <i class="fas fa-book-open w-5 text-lg"></i>
                    <span class="font-medium">Mata Kuliah</span>
                    @if(request()->routeIs('matakuliah.*'))
                        <i class="fas fa-chevron-right ml-auto text-xs text-teal-400"></i>
                    @endif
                </a>
            </nav>
            
            <div class="mx-6 my-4 h-px bg-gradient-to-r from-transparent via-gray-700 to-transparent"></div>
            
            <!-- User Profile -->
            <div class="absolute bottom-0 w-72 p-6 border-t border-gray-700/50 bg-gradient-to-t from-gray-900 to-transparent">
                <div class="flex items-center gap-3 mb-4">
                    <div class="relative">
                        <div class="w-11 h-11 avatar-gradient rounded-full flex items-center justify-center shadow-lg ring-2 ring-teal-500/30">
                            <i class="fas fa-chalkboard-user text-white text-lg"></i>
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full ring-2 ring-gray-900"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                    @csrf
                    <button type="button" onclick="confirmLogout()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-800/50 hover:bg-red-500/20 text-gray-400 hover:text-red-400 rounded-xl transition-all duration-200 border border-gray-700 hover:border-red-500/30">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header -->
            <div class="md:hidden bg-gray-900 shadow-lg p-4 flex justify-between items-center">
                <button id="sidebarToggleMobile" class="text-gray-400 hover:text-white transition">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 avatar-gradient rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <span class="text-sm font-medium text-white">{{ Auth::user()->name }}</span>
                </div>
            </div>
            
            <!-- Mobile Sidebar -->
            <div id="mobileSidebar" class="sidebar-mobile fixed top-0 left-0 h-full w-72 z-50 shadow-2xl overflow-y-auto" style="background: linear-gradient(180deg, #0F172A 0%, #1E293B 100%);">
                <div class="p-6 border-b border-gray-700/50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-white">SISKRS<span class="text-teal-400">+</span></h1>
                            <p class="text-xs text-gray-500">Dosen Panel</p>
                        </div>
                    </div>
                    <button id="closeSidebarMobile" class="text-gray-400 hover:text-white transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <nav class="p-4 space-y-1.5">
                    <a href="{{ route('dosen.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-gray-300 rounded-xl transition">
                        <i class="fas fa-chart-line w-5 text-lg"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('matakuliah.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 text-gray-300 rounded-xl transition">
                        <i class="fas fa-book-open w-5 text-lg"></i>
                        <span class="font-medium">Mata Kuliah</span>
                    </a>
                </nav>
                <div class="absolute bottom-0 w-72 p-6 border-t border-gray-700/50">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 avatar-gradient rounded-full flex items-center justify-center">
                            <i class="fas fa-chalkboard-user text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <button onclick="confirmLogout()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-800/50 hover:bg-red-500/20 text-gray-400 hover:text-red-400 rounded-xl transition">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
            
            <div id="sidebarOverlay" class="fixed inset-0 bg-black/60 hidden z-40 backdrop-blur-sm"></div>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8">
                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                        <div class="mb-6 bg-gradient-to-r from-emerald-50 to-emerald-100/50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl shadow-sm flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100/50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fas fa-exclamation text-white text-sm"></i>
                                </div>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="mb-6 bg-gradient-to-r from-amber-50 to-amber-100/50 border-l-4 border-amber-500 text-amber-700 p-4 rounded-xl shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shadow-sm flex-shrink-0">
                                    <i class="fas fa-info text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold mb-1">Perhatikan kesalahan berikut:</p>
                                    <ul class="list-disc list-inside text-sm space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <script>
        const toggleBtn = document.getElementById('sidebarToggleMobile');
        const closeBtn = document.getElementById('closeSidebarMobile');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        function openSidebar() {
            if (mobileSidebar) mobileSidebar.classList.add('open');
            if (overlay) overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            if (mobileSidebar) mobileSidebar.classList.remove('open');
            if (overlay) overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
        
        function confirmLogout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                document.getElementById('logout-form-sidebar').submit();
            }
        }
    </script>
    
    <!-- LAYOUT UNTUK MAHASISWA DASHBOARD -->
    @elseif($isMahasiswaDashboard)
    
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-fade-up">
            @if(session('success'))
                <div class="mb-6 bg-gradient-to-r from-emerald-50 to-emerald-100/50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center shadow-sm">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100/50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center shadow-sm">
                            <i class="fas fa-exclamation text-white text-sm"></i>
                        </div>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-6 bg-gradient-to-r from-amber-50 to-amber-100/50 border-l-4 border-amber-500 text-amber-700 p-4 rounded-xl shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shadow-sm flex-shrink-0">
                            <i class="fas fa-info text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold mb-1">Perhatikan kesalahan berikut:</p>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- LAYOUT UNTUK WELCOME PAGE & GUEST -->
    @else
    
    <!-- Navbar untuk Guest -->
    <nav class="glass sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="absolute inset-0 bg-teal-400 rounded-xl blur opacity-60"></div>
                        <div class="relative w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        </div>
                    </div>
                    <div>
                        <a href="{{ url('/') }}" class="text-xl font-bold bg-gradient-to-r from-teal-700 to-teal-500 bg-clip-text text-transparent">
                            SISKRS<span class="text-teal-600">+</span>
                        </a>
                        <p class="text-xs text-gray-400 hidden sm:block">Academic Platform</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-teal-600 transition px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login Dosen
                    </a>
                    <a href="{{ route('mahasiswa.login.form') }}" class="btn-primary text-white px-5 py-2 rounded-xl font-semibold shadow-md flex items-center gap-2">
                        <i class="fas fa-user-graduate"></i>
                        Mahasiswa
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-fade-up">
            @if(session('success'))
                <div class="mb-6 bg-gradient-to-r from-emerald-50 to-emerald-100/50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center shadow-sm">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100/50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center shadow-sm">
                            <i class="fas fa-exclamation text-white text-sm"></i>
                        </div>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-6 bg-gradient-to-r from-amber-50 to-amber-100/50 border-l-4 border-amber-500 text-amber-700 p-4 rounded-xl shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shadow-sm flex-shrink-0">
                            <i class="fas fa-info text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold mb-1">Perhatikan kesalahan berikut:</p>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <footer class="mt-20 py-8 border-t border-gray-200/50 bg-white/30 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-[10px]"></i>
                    </div>
                    <span class="text-gray-500 text-sm">SISKRS+ © {{ date('Y') }}</span>
                </div>
                <div class="flex gap-6 text-gray-400 text-sm">
                    <a href="#" class="hover:text-teal-600 transition">Tentang</a>
                    <a href="#" class="hover:text-teal-600 transition">Bantuan</a>
                    <a href="#" class="hover:text-teal-600 transition">Kebijakan</a>
                </div>
            </div>
        </div>
    </footer>
    
    @endif
    
    @stack('scripts')
</body>
</html>