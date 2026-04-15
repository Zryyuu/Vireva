<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vireva') - Luxury Retreat</title>
    
    <!-- Fonts: Space Grotesk & Open Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Libraries -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Open Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Space Grotesk', sans-serif; }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        section[id] {
            scroll-margin-top: 100px;
        }

        /* Lenis Core CSS */
        html.lenis, html.lenis body {
            height: auto;
        }
        .lenis.lenis-smooth {
            scroll-behavior: auto !important;
        }
        .lenis.lenis-smooth [data-lenis-prevent] {
            overscroll-behavior: contain;
        }
        .lenis.lenis-stopped {
            overflow: hidden;
        }
        .lenis.lenis-scrolling iframe {
            pointer-events: none;
        }
    </style>
</head>
<body id="top" class="bg-white text-dark antialiased overflow-x-hidden" x-data="{ mobileMenu: false }">

    <!-- Navbar -->
    <div id="main-nav" class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500 bg-transparent py-6">
        <div class="max-w-7xl mx-auto px-6">
            <nav class="flex justify-between items-center">
                <!-- Branding -->
                <a href="{{ url('/') }}" class="group flex items-center gap-2">
                    <span class="text-3xl font-bold tracking-tighter transition-transform group-hover:scale-105">
                        VIREVA<span class="text-primary">.</span>
                    </span>
                </a>
                
                <!-- Desktop Links -->
                <div class="hidden lg:flex items-center gap-10">
                    <a href="#top" class="nav-link text-sm font-bold uppercase tracking-widest hover:text-primary transition-colors">Beranda</a>
                    <a href="#about" class="nav-link text-sm font-bold uppercase tracking-widest hover:text-primary transition-colors">Tentang</a>
                    <a href="#services" class="nav-link text-sm font-bold uppercase tracking-widest hover:text-primary transition-colors">Layanan</a>
                    <a href="#philosophy" class="nav-link text-sm font-bold uppercase tracking-widest hover:text-primary transition-colors">Filosofi</a>
                    @auth
                        @if(auth()->user()->role == 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold uppercase tracking-widest text-primary border-b-2 border-primary">Panel Admin</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-sm font-bold uppercase tracking-widest text-primary border-b-2 border-primary">Dashboard Saya</a>
                        @endif
                    @endauth
                </div>

                <!-- CTA & Mobile Trigger -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-2">
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm font-bold uppercase tracking-widest hover:text-primary transition-colors">Keluar</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold uppercase tracking-widest hover:text-primary transition-colors border-r border-dark/10 pr-4 mr-2">Masuk</a>
                            <a href="{{ route('register') }}" class="bg-primary text-white px-6 py-2.5 text-sm font-bold uppercase tracking-widest hover:bg-primary-hover transition-all shadow-lg shadow-primary/20">Gabung Member</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenu = true" class="lg:hidden p-2 hover:bg-light transition-colors rounded-lg">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </nav>
        </div>
    </div>

    <!-- Mobile Navigation Sidebar -->
    <div x-show="mobileMenu" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[200] bg-dark/60 backdrop-blur-sm lg:hidden" x-cloak>
        
        <div @click.away="mobileMenu = false" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="absolute right-0 top-0 h-full w-[80%] max-w-sm bg-white shadow-2xl p-8 flex flex-col">
            
            <div class="flex justify-between items-center mb-12">
                <span class="text-2xl font-bold tracking-tighter">VIREVA<span class="text-primary">.</span></span>
                <button @click="mobileMenu = false" class="p-2 hover:bg-light rounded-full transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <div class="flex flex-col gap-6 font-bold text-lg uppercase tracking-widest mb-auto">
                <a @click="mobileMenu = false" href="#top" class="hover:text-primary">Beranda</a>
                <a @click="mobileMenu = false" href="#about" class="hover:text-primary">Tentang</a>
                <a @click="mobileMenu = false" href="#services" class="hover:text-primary">Layanan</a>
                <a @click="mobileMenu = false" href="#philosophy" class="hover:text-primary">Filosofi</a>
                @auth
                    <hr class="border-light">
                    @if(auth()->user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-primary">Panel Admin</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-primary">Dashboard</a>
                    @endif
                @endauth
            </div>

            <div class="mt-12 space-y-4">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-dark text-white py-4 font-bold uppercase tracking-widest hover:bg-primary transition-colors">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center border-2 border-dark py-4 font-bold uppercase tracking-widest hover:bg-dark hover:text-white transition-all">Masuk</a>
                    <a href="{{ route('register') }}" class="block w-full text-center bg-primary text-white py-4 font-bold uppercase tracking-widest hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all">Gabung Member</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer id="footer" class="bg-dark text-white pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 border-b border-white/10 pb-16">
            <!-- About Column -->
            <div class="space-y-6">
                <h1 class="text-white text-3xl font-bold tracking-tighter">VIREVA<span class="text-primary">.</span></h1>
                <p class="leading-relaxed text-sm">Nikmati pengalaman menginap dengan layanan ramah dan desain bangunan yang modern. Kami selalu berusaha menghadirkan kenyamanan di setiap unit villa yang kami tawarkan.</p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center hover:bg-primary hover:border-primary text-white transition-all"><i data-lucide="instagram" class="w-4 h-4"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center hover:bg-primary hover:border-primary text-white transition-all"><i data-lucide="twitter" class="w-4 h-4"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center hover:bg-primary hover:border-primary text-white transition-all"><i data-lucide="facebook" class="w-4 h-4"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="space-y-6">
                <h5 class="text-white text-lg font-bold">Navigasi Cepat</h5>
                <ul class="space-y-4 text-sm font-bold uppercase tracking-widest">
                    <li><a href="{{ request()->is('/') ? '#top' : url('/') }}" class="hover:text-primary transition-colors flex items-center gap-3"><span class="w-4 h-[1px] bg-primary"></span> Beranda</a></li>
                    <li><a href="{{ request()->is('/') ? '#about' : url('/#about') }}" class="hover:text-primary transition-colors flex items-center gap-3"><span class="w-4 h-[1px] bg-primary"></span> Tentang</a></li>
                    <li><a href="{{ request()->is('/') ? '#rooms' : url('/#rooms') }}" class="hover:text-primary transition-colors flex items-center gap-3"><span class="w-4 h-[1px] bg-primary"></span> Koleksi</a></li>
                    <li><a href="{{ request()->is('/') ? '#services' : url('/#services') }}" class="hover:text-primary transition-colors flex items-center gap-3"><span class="w-4 h-[1px] bg-primary"></span> Layanan</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="space-y-6">
                <h5 class="text-white text-lg font-bold">Layanan Kami</h5>
                <ul class="space-y-3 text-[10px] font-bold uppercase tracking-[0.2em]">
                    <li><a href="{{ request()->is('/') ? '#services' : url('/#services') }}" class="hover:text-primary transition-colors flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-primary ring-4 ring-primary/20"></span> Kolam Modern</a></li>
                    <li><a href="{{ request()->is('/') ? '#services' : url('/#services') }}" class="hover:text-primary transition-colors flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-primary ring-4 ring-primary/20"></span> Spa & Relax</a></li>
                    <li><a href="{{ request()->is('/') ? '#services' : url('/#services') }}" class="hover:text-primary transition-colors flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-primary ring-4 ring-primary/20"></span> Private Dining</a></li>
                    <li><a href="{{ request()->is('/') ? '#services' : url('/#services') }}" class="hover:text-primary transition-colors flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-primary ring-4 ring-primary/20"></span> Layanan Sigap</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="space-y-6">
                <h5 class="text-white text-lg font-bold">Hubungi Kami</h5>
                <div class="space-y-4 text-sm">
                    <p class="flex items-start gap-4 hover:text-white transition-colors"><i data-lucide="map-pin" class="text-primary w-5 h-5"></i> Jember, Jawa Timur, ID</p>
                    <p class="flex items-center gap-4 hover:text-white transition-colors"><i data-lucide="phone" class="text-primary w-5 h-5"></i> +62 851 9814 9402</p>
                    <p class="flex items-center gap-4 hover:text-white transition-colors"><i data-lucide="mail" class="text-primary w-5 h-5"></i> achmadnurullah3213@gmail.com</p>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto pt-12 px-6 flex flex-col md:flex-row justify-between items-center text-[10px] uppercase font-bold tracking-[0.2em]">
            <div>&copy; {{ date('Y') }} VIREVA LUXURY STAY. All rights reserved.</div>
            <div class="flex gap-8 mt-6 md:mt-0 opacity-40">
                <a href="#" class="hover:text-white transition-colors">Privasi</a>
                <a href="#" class="hover:text-white transition-colors">Ketentuan</a>
                <a href="#" class="hover:text-white transition-colors">Cookies</a>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/lenis@1.1.13/dist/lenis.min.js"></script>
    <script>
        // 1. Initialize Lenis
        const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            smoothWheel: true,
            orientation: 'vertical',
            gestureOrientation: 'vertical',
            wheelMultiplier: 1,
            smoothTouch: false,
            touchMultiplier: 2,
            infinite: false,
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);

        // 2. Initialize Lucide
        lucide.createIcons();

        // 3. Navbar logic
        const mainNav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                mainNav.classList.add('glass-nav');
                mainNav.classList.remove('py-6');
            } else {
                mainNav.classList.remove('glass-nav');
                mainNav.classList.add('py-6');
            }
        });

        // 4. Smooth Scroll Overide for Lenis
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (!link) return;

            const href = link.getAttribute('href');
            if (!href || href.startsWith('javascript:')) return;

            // Handle Hash & Same-Page Links
            try {
                const url = new URL(href, window.location.origin);
                const isSamePage = url.origin === window.location.origin && url.pathname === window.location.pathname;
                
                // If it's a hash on the same page
                if (isSamePage && url.hash) {
                    const target = document.querySelector(url.hash);
                    if (target) {
                        e.preventDefault();
                        this.mobileMenu = false; // Close menu if open (Alpine will sync)
                        lenis.scrollTo(target, { offset: -100, duration: 1.5 });
                        history.pushState(null, null, url.hash);
                        return;
                    }
                }

                // If it's the home page and we are already there (Logo/Beranda)
                if (isSamePage && url.pathname === '/' && !url.hash) {
                    e.preventDefault();
                    lenis.scrollTo(0, { duration: 1.5 });
                    history.pushState(null, null, '/');
                }
            } catch (err) {
            }
        });
    </script>
</body>
</html>
