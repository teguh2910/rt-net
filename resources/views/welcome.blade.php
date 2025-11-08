<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="RT 03 - Sistem Manajemen Digital Modern untuk Rukun Tetangga">
    <title>RT 03 - Sistem Manajemen Digital Terpadu</title>
    
    <!-- PWA Meta Tags -->
    @include('partials.pwa-meta')
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-slideInLeft {
            animation: slideInLeft 0.8s ease-out forwards;
        }
        
        .animate-slideInRight {
            animation: slideInRight 0.8s ease-out forwards;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .bg-gradient-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body class="font-sans bg-gray-50 text-gray-900">

    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-primary p-3 rounded-2xl shadow-lg">
                        <i class="fas fa-home text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">RT 03</h1>
                        <p class="text-xs text-gray-500 font-medium">RW 05 Digital</p>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-blue-600 font-semibold transition-colors">Beranda</a>
                    <a href="#fitur" class="text-gray-700 hover:text-blue-600 font-semibold transition-colors">Fitur</a>
                    <a href="#tentang" class="text-gray-700 hover:text-blue-600 font-semibold transition-colors">Tentang</a>
                    <a href="#kontak" class="text-gray-700 hover:text-blue-600 font-semibold transition-colors">Kontak</a>
                    @auth
                        <a href="{{ url('/admin') }}" class="bg-gradient-primary text-white px-6 py-2.5 rounded-xl font-semibold hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('filament.admin.auth.login') }}" class="bg-gradient-primary text-white px-6 py-2.5 rounded-xl font-semibold hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-4 space-y-3">
                <a href="#home" class="block py-2 text-gray-700 hover:text-blue-600 font-semibold">Beranda</a>
                <a href="#fitur" class="block py-2 text-gray-700 hover:text-blue-600 font-semibold">Fitur</a>
                <a href="#tentang" class="block py-2 text-gray-700 hover:text-blue-600 font-semibold">Tentang</a>
                <a href="#kontak" class="block py-2 text-gray-700 hover:text-blue-600 font-semibold">Kontak</a>
                @auth
                    <a href="{{ url('/admin') }}" class="block bg-gradient-primary text-white px-4 py-3 rounded-xl font-semibold text-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                @else
                    <a href="{{ route('filament.admin.auth.login') }}" class="block bg-gradient-primary text-white px-4 py-3 rounded-xl font-semibold text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-32 pb-20 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 4s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left animate-slideInLeft">
                    <div class="inline-block mb-6">
                        <span class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-sm font-semibold">
                            🚀 Platform Digital Terpercaya
                        </span>
                    </div>
                    
                    <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                        Kelola RT dengan
                        <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent"> Mudah & Modern</span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Sistem manajemen terintegrasi untuk RT 03 RW 05. Kelola data warga, keuangan, surat, acara, dan dokumentasi dalam satu platform digital yang mudah digunakan.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @auth
                            <a href="{{ url('/admin') }}" class="group bg-gradient-primary text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl transition-all duration-300 inline-flex items-center justify-center">
                                <i class="fas fa-rocket mr-2 group-hover:animate-bounce"></i>
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('filament.admin.auth.login') }}" class="group bg-gradient-primary text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl transition-all duration-300 inline-flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2 group-hover:animate-bounce"></i>
                                Mulai Sekarang
                            </a>
                        @endauth
                        <a href="#fitur" class="bg-white text-gray-900 px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl transition-all duration-300 inline-flex items-center justify-center border-2 border-gray-200 hover:border-blue-600">
                            <i class="fas fa-info-circle mr-2"></i>
                            Pelajari Fitur
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">50+</div>
                            <div class="text-sm text-gray-600 font-medium">Warga</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">100+</div>
                            <div class="text-sm text-gray-600 font-medium">Transaksi</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-pink-600">25+</div>
                            <div class="text-sm text-gray-600 font-medium">Pengumuman</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Illustration -->
                <div class="relative animate-slideInRight">
                    <div class="relative z-10">
                        <div class="bg-white rounded-3xl shadow-2xl p-8 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 bg-blue-50 p-4 rounded-xl">
                                    <div class="bg-blue-500 p-3 rounded-xl">
                                        <i class="fas fa-users text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">Data Warga</div>
                                        <div class="text-sm text-gray-500">Terorganisir Rapi</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3 bg-green-50 p-4 rounded-xl">
                                    <div class="bg-green-500 p-3 rounded-xl">
                                        <i class="fas fa-chart-line text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">Laporan Keuangan</div>
                                        <div class="text-sm text-gray-500">Transparan & Real-time</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3 bg-purple-50 p-4 rounded-xl">
                                    <div class="bg-purple-500 p-3 rounded-xl">
                                        <i class="fas fa-envelope text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">Surat Digital</div>
                                        <div class="text-sm text-gray-500">Otomatis & Efisien</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Icons -->
                    <div class="absolute -top-10 -right-10 bg-yellow-400 p-4 rounded-2xl shadow-xl animate-float">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <div class="absolute -bottom-10 -left-10 bg-pink-400 p-4 rounded-2xl shadow-xl animate-float" style="animation-delay: 3s;">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16 animate-fadeInUp">
                <span class="text-blue-600 font-bold text-lg">FITUR UNGGULAN</span>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-gray-900 mt-4 mb-6">
                    Solusi Lengkap untuk RT Anda
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Semua fitur yang Anda butuhkan untuk mengelola Rukun Tetangga secara profesional dan efisien
                </p>
            </div>
            
            <!-- Features Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl">
                    <div class="bg-blue-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-users text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Data Warga</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola informasi lengkap warga RT termasuk KK, NIK, status, dan riwayat dengan database terorganisir.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl">
                    <div class="bg-green-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-wallet text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Keuangan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Catat pemasukan, pengeluaran, dan buat laporan keuangan bulanan yang transparan dan akurat.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-gradient-to-br from-yellow-50 to-yellow-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl">
                    <div class="bg-yellow-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-bullhorn text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pengumuman</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sebarkan informasi penting dengan cepat melalui notifikasi email dan WhatsApp otomatis.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl">
                    <div class="bg-purple-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-file-alt text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Surat Digital</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Buat surat keterangan domisili, pengantar, dan usaha dengan template resmi dan nomor otomatis.
                    </p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card bg-gradient-to-br from-red-50 to-red-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl">
                    <div class="bg-red-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-calendar-check text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Acara & Kegiatan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Atur jadwal rapat, kerja bakti, dan acara RT dengan sistem undangan dan reminder digital.
                    </p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card bg-gradient-to-br from-indigo-50 to-indigo-100 p-8 rounded-2xl shadow-lg hover:shadow-2xl">
                    <div class="bg-indigo-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-folder-open text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Dokumentasi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Simpan dan kelola semua dokumen penting RT dengan sistem arsip digital yang aman.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-20 bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left Content -->
                <div>
                    <span class="text-blue-600 font-bold text-lg">TENTANG SISTEM</span>
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-gray-900 mt-4 mb-6">
                        Transformasi Digital RT 03
                    </h2>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Sistem manajemen modern yang dirancang khusus untuk memudahkan pengurus RT dalam mengelola administrasi dan komunikasi dengan warga.
                    </p>
                    
                    <!-- Benefits -->
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-500 p-2 rounded-lg mt-1">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Akses Kapan Saja</h4>
                                <p class="text-gray-600">Kelola RT 24/7 dari mana saja melalui internet</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-500 p-2 rounded-lg mt-1">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Data Aman Terpusat</h4>
                                <p class="text-gray-600">Semua informasi tersimpan aman di cloud server</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-500 p-2 rounded-lg mt-1">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Transparan & Akuntabel</h4>
                                <p class="text-gray-600">Laporan dapat diakses dan dipantau warga</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Content - Role Cards -->
                <div class="bg-white rounded-3xl shadow-2xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">
                        Sistem Akses Berdasarkan Peran
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Admin -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-5 rounded-xl border-2 border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-blue-500 p-3 rounded-xl">
                                        <i class="fas fa-crown text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Admin RT</h4>
                                        <p class="text-sm text-gray-600">Akses penuh sistem</p>
                                    </div>
                                </div>
                                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold">FULL</span>
                            </div>
                        </div>
                        
                        <!-- Ketua RT -->
                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-5 rounded-xl border-2 border-green-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-green-500 p-3 rounded-xl">
                                        <i class="fas fa-user-tie text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Ketua RT</h4>
                                        <p class="text-sm text-gray-600">Kelola surat & pengumuman</p>
                                    </div>
                                </div>
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold">MANAGER</span>
                            </div>
                        </div>
                        
                        <!-- Bendahara -->
                        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-5 rounded-xl border-2 border-yellow-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-yellow-500 p-3 rounded-xl">
                                        <i class="fas fa-calculator text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Bendahara</h4>
                                        <p class="text-sm text-gray-600">Kelola keuangan RT</p>
                                    </div>
                                </div>
                                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">FINANCE</span>
                            </div>
                        </div>
                        
                        <!-- Warga -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-5 rounded-xl border-2 border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gray-500 p-3 rounded-xl">
                                        <i class="fas fa-user text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Warga</h4>
                                        <p class="text-sm text-gray-600">Lihat informasi & laporan</p>
                                    </div>
                                </div>
                                <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-bold">VIEW</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-primary relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-4xl lg:text-5xl font-extrabold text-white mb-6">
                Siap Modernisasi RT Anda?
            </h2>
            <p class="text-xl text-blue-100 mb-10 leading-relaxed">
                Bergabunglah dengan era digital dan nikmati kemudahan mengelola RT dengan sistem yang modern, efisien, dan mudah digunakan.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ url('/admin') }}" class="group bg-white text-blue-600 px-10 py-5 rounded-xl font-bold text-xl shadow-2xl hover:shadow-3xl transition-all duration-300 inline-flex items-center justify-center">
                        <i class="fas fa-rocket mr-2 group-hover:animate-bounce"></i>
                        Mulai Kelola RT Sekarang
                    </a>
                @else
                    <a href="{{ route('filament.admin.auth.login') }}" class="group bg-white text-blue-600 px-10 py-5 rounded-xl font-bold text-xl shadow-2xl hover:shadow-3xl transition-all duration-300 inline-flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2 group-hover:animate-bounce"></i>
                        Masuk ke Sistem
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-12">
                <!-- Column 1 -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-gradient-primary p-3 rounded-2xl">
                            <i class="fas fa-home text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">RT 03</h3>
                            <p class="text-gray-400 text-sm">RW 05</p>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-6">
                        Sistem manajemen digital modern untuk memudahkan administrasi dan komunikasi RT dengan warga.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-gray-800 p-3 rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-gray-800 p-3 rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="bg-gray-800 p-3 rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Column 2 -->
                <div>
                    <h4 class="text-xl font-bold mb-6">Kontak Kami</h4>
                    <div class="space-y-4 text-gray-400">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-blue-400 mt-1"></i>
                            <span>Jl. Merdeka No. 1<br>RT 03 RW 05<br>Jakarta Selatan</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-blue-400"></i>
                            <span>(021) 1234-5678</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-blue-400"></i>
                            <span>rt03rw05@email.com</span>
                        </div>
                    </div>
                </div>
                
                <!-- Column 3 -->
                <div>
                    <h4 class="text-xl font-bold mb-6">Menu Cepat</h4>
                    <div class="space-y-3">
                        <a href="#home" class="block text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                        <a href="#fitur" class="block text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-star mr-2"></i>Fitur
                        </a>
                        <a href="#tentang" class="block text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-info-circle mr-2"></i>Tentang
                        </a>
                        @auth
                            <a href="{{ url('/admin') }}" class="block text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('filament.admin.auth.login') }}" class="block text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; 2025 RT 03 RW 05. Dibuat dengan <i class="fas fa-heart text-red-500"></i> untuk masyarakat Indonesia.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu
                    mobileMenu.classList.add('hidden');
                }
            });
        });
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fadeInUp');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.feature-card').forEach(card => {
            observer.observe(card);
        });
    </script>

    <!-- PWA Install Button -->
    @include('partials.pwa-install-button')

</body>
</html>