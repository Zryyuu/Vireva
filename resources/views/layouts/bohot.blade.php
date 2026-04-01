<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bohot') - Kemewahan Tak Terlupakan</title>
    
    <!-- Fonts: Outfit (Luxury Feel) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles: Tailwind v4 -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js (Interactivity) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Animate Source -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        [x-cloak] { display: none !important; }
        
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero-section {
            background: linear-gradient(rgba(30, 58, 138, 0.8), rgba(30, 58, 138, 0.5)), url('/images/hero.png');
            background-size: cover;
            background-position: center;
        }


    </style>
</head>
<body class="bg-secondary text-dark antialiased selection:bg-accent selection:text-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-500 py-4 px-6 md:px-12"
         :class="scrolled ? 'bg-primary/95 backdrop-blur-md py-3 shadow-2xl' : 'bg-transparent py-5'">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 group">
                <div class="p-2 bg-accent rounded-lg group-hover:rotate-12 transition-transform duration-300">
                    <i data-lucide="building-2" class="text-primary w-6 h-6"></i>
                </div>
                <span class="text-2xl font-bold tracking-[0.2em] text-accent uppercase">'Bohot</span>
            </a>
            
            <!-- Desktop Links -->
            <div class="hidden md:flex items-center gap-10">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold tracking-wide hover:text-accent transition-colors duration-300 text-white">Dashboard</a>
                    <a href="/rooms" class="text-sm font-semibold tracking-wide hover:text-accent transition-colors duration-300 text-white">Cari Kamar</a>
                    <a href="/bookings" class="text-sm font-semibold tracking-wide hover:text-accent transition-colors duration-300 text-white">Booking Saya</a>
                    <a href="{{ route('profile.edit') }}" class="text-sm font-semibold tracking-wide hover:text-accent transition-colors duration-300 text-white">Profil</a>
                @else
                    <a href="/" class="text-sm font-semibold tracking-wide hover:text-accent transition-colors duration-300 text-white">Beranda</a>
                    <a href="#kamar" class="text-sm font-semibold tracking-wide hover:text-accent transition-colors duration-300 text-white">Cari Kamar</a>
                    <a href="#layanan" class="text-sm font-semibold tracking-wide hover:text-accent transition-colors duration-300 text-white">Layanan</a>
                    <a href="#promo" class="text-sm font-semibold tracking-wide hover:text-accent transition-colors duration-300 text-white">Promo</a>
                @endauth
            </div>

            <!-- CTA -->
            <div class="flex items-center gap-4">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="group relative px-6 py-2 overflow-hidden rounded-full border-2 border-accent/40 text-white font-bold transition-all duration-300 hover:border-accent">
                            <span class="relative z-10 flex items-center gap-2"><i data-lucide="log-out" class="w-4 h-4"></i> Logout</span>
                            <div class="absolute inset-0 bg-accent translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        </button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="px-6 py-2.5 text-white font-bold hover:text-accent transition-colors duration-300">Daftar</a>
                    <a href="{{ route('login') }}" class="group relative px-8 py-2.5 overflow-hidden rounded-xl border border-accent/40 text-white font-bold transition-all duration-300 hover:border-transparent">
                        <span class="relative z-10">Masuk</span>
                        <div class="absolute -inset-1 bg-accent translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary pt-20 pb-10 px-6 md:px-12 text-white/70">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-white/5 pb-16">
            <div class="col-span-1 md:col-span-1">
                <div class="text-3xl font-bold text-accent mb-6 tracking-widest">'Bohot</div>
                <p class="leading-relaxed mb-6">Membawa pengalaman menginap ke tingkat yang lebih tinggi dengan sentuhan kemewahan modern dan pelayanan eksklusif.</p>
                <div class="flex gap-4">
                    <a href="#" class="p-2 bg-white/5 rounded-full hover:bg-accent hover:text-primary transition-all"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                    <a href="#" class="p-2 bg-white/5 rounded-full hover:bg-accent hover:text-primary transition-all"><i data-lucide="facebook" class="w-5 h-5"></i></a>
                    <a href="#" class="p-2 bg-white/5 rounded-full hover:bg-accent hover:text-primary transition-all"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                </div>
            </div>
            
            <div class="md:pl-10">
                <h4 class="text-accent font-bold mb-6 text-lg uppercase tracking-wider">Navigasi</h4>
                <ul class="space-y-4 font-medium">
                    <li><a href="#" class="hover:text-accent transition-colors">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Cari Kamar</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Testimoni</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Lokasi</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-accent font-bold mb-6 text-lg uppercase tracking-wider">Layanan</h4>
                <ul class="space-y-4 font-medium">
                    <li><a href="#" class="hover:text-accent transition-colors">Reservasi Online</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Layanan VIP</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Events & Wedding</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Bantuan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-accent font-bold mb-6 text-lg uppercase tracking-wider">Kontak</h4>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-5 h-5 text-accent shrink-0"></i>
                        <span>Jl. Kemewahan No. 8, Jakarta Selatan</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i data-lucide="phone" class="w-5 h-5 text-accent shrink-0"></i>
                        <span>+62 21 8888 0000</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i data-lucide="mail" class="w-5 h-5 text-accent shrink-0"></i>
                        <span>reservasi@bohot.id</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto pt-8 text-center text-sm font-medium tracking-wide">
            &copy; {{ date('Y') }} <span class="text-accent">'BOHOT</span> HOTEL GROUP. Seluruh hak cipta dilindungi undang-undang.
        </div>
    </footer>

    <script>
        lucide.createIcons();


    </script>
</body>
</html>
