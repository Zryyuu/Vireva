@extends('layouts.bohot')

@section('title', 'Kenyamanan Eksklusif')

@section('content')
<!-- Hero Section -->
<section class="min-h-screen flex items-center justify-center relative hero-section px-6 md:px-12 py-32 overflow-hidden shadow-2xl">
    <div class="max-w-5xl w-full text-center space-y-10 relative z-10 animate__animated animate__fadeIn">
        
        <div class="space-y-4">
            <h1 class="text-5xl md:text-8xl font-bold text-white tracking-widest leading-loose italic drop-shadow-2xl">
                Pesan <span class="text-accent">Kamar</span> Impian Anda
            </h1>
            <p class="text-lg md:text-2xl text-white/90 font-medium tracking-widest max-w-3xl mx-auto drop-shadow-lg italic">
                Pesan Penginapan Mewah & Tak Terlupakan Bersama Bohot.
            </p>
        </div>
        
        <!-- Search Glassmorphism Card -->
        <div class="glass p-8 md:p-12 rounded-3xl shadow-[-1px_1px_5px_0px_#f9fafb08,1px_-1px_5px_0px_#f9fafb08,0px_45px_100px_0px_#00000045] flex flex-wrap lg:flex-nowrap gap-8 items-end border border-white/20 transform transition-all hover:scale-[1.01] duration-500">
            
            <div class="flex-1 min-w-[200px] text-left">
                <label class="block text-white/70 text-sm font-bold mb-3 ml-6 uppercase tracking-widest">Check-in</label>
                <div class="bg-white/95 rounded-xl px-8 py-5 flex items-center gap-4 border border-white/10 shadow-inner group transition-all hover:shadow-accent/20">
                    <i data-lucide="calendar" class="w-5 h-5 text-primary group-hover:text-accent transition-colors"></i>
                    <input type="text" value="18 Nov 2026" class="w-full font-bold focus:outline-none text-primary bg-transparent cursor-pointer">
                </div>
            </div>
            
            <div class="flex-1 min-w-[200px] text-left">
                <label class="block text-white/70 text-sm font-bold mb-3 ml-6 uppercase tracking-widest">Check-out</label>
                <div class="bg-white/95 rounded-xl px-8 py-5 flex items-center gap-4 border border-white/10 shadow-inner group transition-all hover:shadow-accent/20">
                    <i data-lucide="calendar" class="w-5 h-5 text-primary group-hover:text-accent transition-colors"></i>
                    <input type="text" value="22 Nov 2026" class="w-full font-bold focus:outline-none text-primary bg-transparent cursor-pointer">
                </div>
            </div>
            
            <div class="flex-1 min-w-[200px] text-left">
                <label class="block text-white/70 text-sm font-bold mb-3 ml-6 uppercase tracking-widest">Tamu</label>
                <div class="bg-white/95 rounded-xl px-8 py-5 flex items-center gap-4 border border-white/10 shadow-inner group transition-all hover:shadow-accent/20">
                    <i data-lucide="users" class="w-5 h-5 text-primary group-hover:text-accent transition-colors"></i>
                    <select class="w-full font-bold focus:outline-none bg-transparent text-primary appearance-none">
                        <option>2 Dewasa, 1 Anak</option>
                        <option>1 Dewasa</option>
                        <option>2 Dewasa</option>
                        <option>3+ Dewasa</option>
                    </select>
                </div>
            </div>
            
            <button class="bg-accent hover:bg-accent-hover text-white px-12 py-5 rounded-xl font-bold text-lg uppercase tracking-widest shadow-2xl transition-all duration-300 transform hover:-translate-y-2 hover:shadow-accent/40 flex items-center gap-3 mx-auto lg:mx-0 min-w-[240px] justify-center">
                <span>CARI KAMAR</span>
                <i data-lucide="search" class="w-5 h-5"></i>
            </button>
        </div>
    </div>
    

</section>

<!-- Featured Rooms Section -->
<section id="kamar" class="py-32 px-6 md:px-12 bg-white relative">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-end mb-20">
            <div class="max-w-xl text-left space-y-4">
                <span class="text-accent font-bold uppercase tracking-[0.4em] text-sm block italic">Koleksi Terkurasi</span>
                <h2 class="text-5xl md:text-7xl font-bold text-primary italic tracking-tight leading-tight">Cari Kamar <br>Unggulan</h2>
                <div class="bg-accent h-1.5 w-32 rounded-xl mt-4"></div>
            </div>
            <a href="/rooms" class="hidden md:flex items-center gap-3 font-bold text-accent uppercase tracking-widest group border-b-2 border-transparent hover:border-accent pb-1 transition-all duration-300 text-sm">
                Lihat Semua Kamar 
                <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-2 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
            
            <!-- Room Card 1 -->
            <div class="group relative rounded-3xl overflow-hidden shadow-2xl bg-white h-[650px] flex flex-col justify-end p-12">
                <img src="https://images.unsplash.com/photo-1590490359683-658d3d23f972?q=80&w=1200" 
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Deluxe Ocean View">
                <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/30 to-transparent opacity-90"></div>
                
                <!-- Room Badge -->
                <div class="absolute top-8 left-8 z-20">
                    <span class="glass px-6 py-2.5 rounded-xl text-white text-xs font-bold flex items-center gap-2 border border-white/30 backdrop-blur-md">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span> TERSEDIA
                    </span>
                </div>

                <div class="relative z-10 space-y-6">
                    <div class="flex justify-between items-end">
                        <div class="space-y-2">
                            <h3 class="text-4xl font-bold text-white tracking-widest italic tracking-tight">Deluxe Ocean View</h3>
                            <div class="flex gap-4 text-white/70 text-sm font-medium">
                                <span class="flex items-center gap-1.5"><i data-lucide="maximize" class="w-4 h-4 text-accent"></i> 45 m²</span>
                                <span class="flex items-center gap-1.5"><i data-lucide="users" class="w-4 h-4 text-accent"></i> 2 Tamu</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <div class="flex gap-1 mb-1">
                                @for($i=0; $i<5; $i++) <i data-lucide="star" class="w-4 h-4 fill-accent text-accent"></i> @endfor
                            </div>
                            <span class="text-white/60 text-xs font-bold">24 REVIEW</span>
                        </div>
                    </div>
                    
                    <p class="text-white/80 leading-relaxed font-medium">Pengalaman menginap tak tertandingi dengan pemandangan cakrawala laut yang biru langsung dari tempat tidur Anda.</p>
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end pt-6 border-t border-white/10 gap-6">
                        <div class="space-y-1">
                            <span class="text-xs text-white/50 font-bold uppercase tracking-widest">Mulai Dari</span>
                            <div class="text-white leading-none">
                                <span class="text-3xl font-extrabold text-accent tracking-widest">Rp 3.800.000</span>
                                <span class="text-sm opacity-60 font-medium whitespace-nowrap">/ MALAM</span>
                            </div>
                        </div>
                        <button class="bg-accent hover:bg-white hover:text-primary px-10 py-4 rounded-xl font-bold text-sm tracking-widest transition-all duration-300 transform group-hover:-translate-y-1 shadow-xl shrink-0">
                            Pesan Kamar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Room Card 2 -->
            <div class="group relative rounded-3xl overflow-hidden shadow-2xl bg-white h-[650px] flex flex-col justify-end p-12">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200" 
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Premium Mountain Suite">
                <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/30 to-transparent opacity-90"></div>
                
                <!-- Room Badge -->
                <div class="absolute top-8 left-8 z-20">
                    <span class="glass px-6 py-2.5 rounded-xl text-white text-xs font-bold flex items-center gap-2 border border-white/30 backdrop-blur-md">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span> TERSEDIA
                    </span>
                </div>

                <div class="relative z-10 space-y-6">
                    <div class="flex justify-between items-end">
                        <div class="space-y-2">
                            <h3 class="text-4xl font-bold text-white tracking-widest italic tracking-tight">Premium Mountain Suite</h3>
                            <div class="flex gap-4 text-white/70 text-sm font-medium">
                                <span class="flex items-center gap-1.5"><i data-lucide="maximize" class="w-4 h-4 text-accent"></i> 62 m²</span>
                                <span class="flex items-center gap-1.5"><i data-lucide="users" class="w-4 h-4 text-accent"></i> 4 Tamu</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <div class="flex gap-1 mb-1">
                                @for($i=0; $i<5; $i++) <i data-lucide="star" class="w-4 h-4 fill-accent text-accent"></i> @endfor
                            </div>
                            <span class="text-white/60 text-xs font-bold">18 REVIEW</span>
                        </div>
                    </div>
                    
                    <p class="text-white/80 leading-relaxed font-medium">Hiruplah udara pegunungan yang segar di suite eksklusif kami yang dirancang untuk kenyamanan keluarga Anda.</p>
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end pt-6 border-t border-white/10 gap-6">
                        <div class="space-y-1">
                            <span class="text-xs text-white/50 font-bold uppercase tracking-widest">Mulai Dari</span>
                            <div class="text-white leading-none">
                                <span class="text-3xl font-extrabold text-accent tracking-widest">Rp 5.200.000</span>
                                <span class="text-sm opacity-60 font-medium whitespace-nowrap">/ MALAM</span>
                            </div>
                        </div>
                        <button class="bg-accent hover:bg-white hover:text-primary px-10 py-4 rounded-xl font-bold text-sm tracking-widest transition-all duration-300 transform group-hover:-translate-y-1 shadow-xl shrink-0">
                            Pesan Kamar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Facilities Grid -->
<section id="layanan" class="bg-primary py-32 px-6 md:px-12 text-white relative overflow-hidden">
    <!-- Decor Circles -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-accent/5 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-accent/5 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-24">
            <span class="text-accent font-bold uppercase tracking-[0.5em] text-sm block italic mb-4">Pengalaman Tak Terbatas</span>
            <h2 class="text-5xl font-bold italic tracking-tight uppercase leading-none">Layanan & Fasilitas Kami</h2>
            <p class="text-white/50 mt-6 max-w-2xl mx-auto font-medium">Kami mengutamakan setiap detail untuk memastikan setiap detik kunjungan Anda menjadi kenangan yang manis.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="glass p-12 rounded-3xl group hover:scale-105 transition-all duration-500 hover:shadow-accent/10 space-y-6">
                <div class="p-4 bg-accent/10 rounded-2xl w-fit mx-auto group-hover:bg-accent transition-colors duration-300">
                    <i data-lucide="wifi" class="w-10 h-10 text-accent group-hover:text-primary transition-colors"></i>
                </div>
                <h4 class="text-xl font-bold tracking-widest italic tracking-tight">PHE-WiFi</h4>
                <p class="text-white/40 text-sm font-medium">Koneksi serat optik kecepatan tinggi di seluruh area.</p>
            </div>
            
            <div class="glass p-12 rounded-3xl group hover:scale-105 transition-all duration-500 hover:shadow-accent/10 space-y-6">
                <div class="p-4 bg-accent/10 rounded-2xl w-fit mx-auto group-hover:bg-accent transition-colors duration-300">
                    <i data-lucide="waves" class="w-10 h-10 text-accent group-hover:text-primary transition-colors"></i>
                </div>
                <h4 class="text-xl font-bold tracking-widest italic tracking-tight">Ocean Pool</h4>
                <p class="text-white/40 text-sm font-medium">Kolam renang infinity dengan pemandangan laut.</p>
            </div>

            <div class="glass p-12 rounded-3xl group hover:scale-105 transition-all duration-500 hover:shadow-accent/10 space-y-6">
                <div class="p-4 bg-accent/10 rounded-2xl w-fit mx-auto group-hover:bg-accent transition-colors duration-300">
                    <i data-lucide="dumbbell" class="w-10 h-10 text-accent group-hover:text-primary transition-colors"></i>
                </div>
                <h4 class="text-xl font-bold tracking-widest italic tracking-tight">Luxury Gym</h4>
                <p class="text-white/40 text-sm font-medium">Peralatan kebugaran modern & personal trainer.</p>
            </div>

            <div class="glass p-12 rounded-3xl group hover:scale-105 transition-all duration-500 hover:shadow-accent/10 space-y-6">
                <div class="p-4 bg-accent/10 rounded-2xl w-fit mx-auto group-hover:bg-accent transition-colors duration-300">
                    <i data-lucide="utensils" class="w-10 h-10 text-accent group-hover:text-primary transition-colors"></i>
                </div>
                <h4 class="text-xl font-bold tracking-widest italic tracking-tight">Fine Dining</h4>
                <p class="text-white/40 text-sm font-medium">Restoran bintang lima dengan koki internasional.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section id="promo" class="py-24 px-6 md:px-12 bg-white">
    <div class="max-w-7xl mx-auto bg-accent rounded-3xl p-12 md:p-20 relative overflow-hidden shadow-2xl flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="absolute -top-10 -left-10 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        
        <div class="relative z-10 space-y-6 max-w-2xl text-center md:text-left text-primary">
            <h2 class="text-4xl md:text-6xl font-bold tracking-tight italic leading-tight">Dapatkan Penawaran Eksklusif Member</h2>
            <p class="text-lg md:text-xl font-semibold opacity-80">Nikmati diskon hingga 25% untuk pemesanan pertama Anda melalui akun 'Bohot.</p>
        </div>
        
        <div class="relative z-10 shrink-0">
            <a href="{{ route('register') }}" class="inline-block bg-primary hover:bg-primary-dark text-white px-12 py-6 rounded-xl font-bold text-xl tracking-widest uppercase shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                Daftar Sekarang
            </a>
        </div>
    </div>
</section>
@endsection
