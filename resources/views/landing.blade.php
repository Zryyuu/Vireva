@extends('layouts.bohot')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Start -->
    <section class="relative min-h-[90vh] lg:min-h-[85vh] flex items-center pt-32 lg:pt-24 pb-12 overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-6 w-full relative z-10">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-20 items-center">
                <!-- Konten Kiri (Text) -->
                <div class="lg:col-span-6 space-y-8 lg:space-y-10 animate__animated animate__fadeInLeft text-center lg:text-left order-2 lg:order-1">
                    <div class="inline-flex items-center gap-3 bg-light px-4 py-2 rounded-full border border-dark/5 mx-auto lg:mx-0">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-dark/60">Grand Opening 2026</span>
                    </div>
                    
                    <div class="space-y-4 lg:space-y-6">
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.2] tracking-tighter">
                            Pengalaman <br class="hidden sm:block">
                            Menginap yang <br class="hidden lg:block">
                            <span class="text-primary italic">Lebih Personal</span>.
                        </h1>
                        <p class="text-secondary text-base lg:text-lg max-w-lg mx-auto lg:mx-0 leading-relaxed font-light">
                            Nikmati pengalaman menginap butik dengan paduan desain modern dan layanan yang lebih personal.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 items-center justify-center lg:justify-start">
                        <a href="#rooms" class="w-full sm:w-auto text-center bg-dark text-white px-10 py-5 font-bold uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-dark/10">Lihat Koleksi</a>
                        <a href="#about" class="group flex items-center gap-4 px-6 py-5 font-bold uppercase tracking-widest hover:text-primary transition-all">
                            Visi Kami 
                            <span class="w-10 h-10 rounded-full border border-dark/10 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                                <i data-lucide="play" class="w-3 h-3 fill-current"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Media Kanan (Image) -->
                <div class="lg:col-span-6 relative animate__animated animate__fadeInRight animate__delay-1s mt-12 lg:mt-0 order-1 lg:order-2">
                    <div class="relative group">
                        <!-- Gambar Utama -->
                        <div class="aspect-[16/10] sm:aspect-video lg:aspect-[16/10] bg-dark overflow-hidden shadow-[0_50px_100px_-20px_rgba(0,0,0,0.25)]">
                            <img src="{{ asset('images/hero.jpg') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[3000ms]" alt="Hero Image">
                        </div>
                        
                        <!-- Kotak Aksen (Kiri Atas - Disesuaikan) -->
                        <div class="absolute -top-4 -left-4 w-24 h-24 bg-primary/5 -z-10 hidden sm:block"></div>

                        <!-- Persegi Panjang Dekoratif (Full Ukuran Image - Bawah Kanan) -->
                        <div class="absolute -bottom-6 -right-6 lg:-bottom-12 lg:-right-12 w-full h-full bg-primary/20 -z-10 hidden sm:block"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Elemen Latar Belakang -->
        <div class="absolute top-0 right-0 w-full lg:w-1/3 h-full bg-light/50 -z-10 hidden lg:block"></div>
    </section>

    <!-- Fitur Cepat -->
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                @foreach([
                    ['icon' => 'zap', 'title' => 'Desain Khusus', 'desc' => 'Identitas arsitektural unik untuk setiap villa kami.', 'delay' => ''],
                    ['icon' => 'infinity', 'title' => 'Peluncuran Perdana', 'desc' => 'Jadilah yang pertama menikmati koleksi baru kami.', 'delay' => 'animate__delay-0.5s'],
                    ['icon' => 'eye-off', 'title' => 'Privasi Total', 'desc' => 'Lokasi tenang yang dirancang untuk kenyamanan maksimal Anda.', 'delay' => 'animate__delay-1s'],
                    ['icon' => 'coffee', 'title' => 'Layanan Modern', 'desc' => 'Pendekatan baru dan modern untuk setiap kebutuhan Anda.', 'delay' => 'animate__delay-1.5s']
                ] as $feature)
                <div class="group p-8 border border-dark/10 shadow-sm hover:border-primary/30 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 animate__animated animate__fadeInUp {{ $feature['delay'] }}">
                    <div class="w-16 h-16 flex items-center justify-center bg-light text-primary mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                        <i data-lucide="{{ $feature['icon'] }}" class="w-6 h-6"></i>
                    </div>
                    <h5 class="text-xl mb-3 uppercase tracking-tighter font-bold">{{ $feature['title'] }}</h5>
                    <p class="text-sm text-secondary leading-relaxed font-light">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Tentang Dimulai -->
    <section id="about" class="py-24 lg:py-32 bg-light overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-20 items-center">
                <div class="relative grid grid-cols-1 sm:grid-cols-2 gap-6 animate__animated animate__fadeInLeft">
                    <div class="space-y-4 pt-12">
                        <img src="{{ asset('images/about_1.jpg') }}" class="w-full h-64 sm:h-80 object-cover shadow-2xl hover:scale-105 transition-transform duration-700" alt="Tentang 1">
                        <div class="bg-primary p-6 sm:p-10 text-white shadow-xl">
                            <h4 class="text-2xl sm:text-3xl font-bold mb-1 italic tracking-tighter">Est 2026</h4>
                            <p class="text-[8px] sm:text-[10px] font-bold uppercase tracking-[0.2em] opacity-80">Standar Baru Dimulai</p>
                        </div>
                    </div>
                    <div>
                        <img src="{{ asset('images/about_2.jpg') }}" class="w-full h-[24rem] sm:h-[32rem] object-cover shadow-2xl hover:scale-105 transition-transform duration-700" alt="Tentang 2">
                    </div>
                </div>
                
                <div class="animate__animated animate__fadeInRight text-center lg:text-left mt-12 lg:mt-0">
                    <div class="inline-block border-b-2 border-primary pb-2 mb-6">
                        <span class="text-xs font-bold uppercase tracking-[0.3em] text-primary">Konsep Vireva</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-8 tracking-tighter leading-tight">Tempat Terbaik untuk <br class="hidden sm:block"> Menemukan Ketenangan</h2>
                    <p class="text-secondary text-base lg:text-lg mb-10 leading-relaxed font-light mx-auto lg:mx-0 max-w-xl">
                        Vireva hadir untuk memberikan pengalaman menginap yang tulus dan personal. Kami fokus menciptakan suasana yang tenang agar Anda merasa betah seperti berada di properti pribadi sendiri.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 lg:gap-8 mb-12 text-left">
                        @foreach(['Villa Eksklusif', 'Layanan 24 Jam', 'Lingkungan Hijau', 'Rekomendasi Spesial'] as $check)
                        <div class="flex items-center gap-4 group">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-sm shrink-0">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </div>
                            <span class="font-bold text-xs sm:text-sm uppercase tracking-widest text-dark/80">{{ $check }}</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <a href="#" class="inline-flex items-center gap-4 text-dark font-bold uppercase tracking-[0.2em] group">
                        Cerita Kami
                        <span class="w-12 h-12 rounded-full border border-dark/10 flex items-center justify-center group-hover:bg-dark group-hover:text-white transition-all">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Kamar Dimulai -->
    <section id="rooms" class="pt-24 lg:pt-32 pb-20 lg:pb-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-2 text-center animate__animated animate__fadeInUp">
                <span class="text-xs font-bold uppercase tracking-[0.4em] text-primary mb-4 block">Koleksi Kami</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tighter">Koleksi Terbaru</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($kamar as $item)
                <div class="group relative aspect-[4/5] overflow-hidden bg-dark animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    @if($item->foto)
                        <img src="{{ Storage::url($item->foto) }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-[2000ms]" alt="{{ $item->tipe_kamar }}">
                    @else
                        <div class="w-full h-full bg-light flex flex-col items-center justify-center text-dark/20 uppercase font-bold tracking-widest p-8 text-center">
                            <i data-lucide="image" class="w-12 h-12 mb-4"></i>
                            Gambar Menyusul
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-dark/90 via-dark/20 to-transparent p-6 sm:p-10 flex flex-col justify-end transform transition-transform duration-700">
                        <div class="space-y-2 translate-y-8 group-hover:translate-y-0 transition-transform duration-500">
                            <div class="text-[10px] font-bold uppercase tracking-[0.3em] text-primary">{{ $item->tipe_kamar }}</div>
                            <h3 class="text-2xl sm:text-3xl text-white font-bold tracking-tighter mb-4">Villa Room {{ $item->nomor_kamar }}</h3>
                            <a href="{{ route('bookings.create', $item->id) }}" class="inline-block bg-white text-dark px-6 py-3 font-bold text-[10px] sm:text-xs uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity duration-700">Pesan Sekarang</a>
                        </div>
                    </div>
                    
                    <div class="absolute top-6 right-6 sm:top-8 sm:right-8 bg-primary/90 text-white px-4 py-2 text-[10px] sm:text-xs font-bold tracking-tighter">
                        MULAI {{ number_format($item->harga_permalam/1000, 0) }}K
                    </div>
                </div>
                @empty
                <div class="col-span-full py-4 text-center text-secondary/60 italic font-light tracking-wide text-xl">
                    Koleksi baru akan segera hadir.
                </div>
                <div class="col-span-full h-12 lg:h-16"></div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Fasilitas Dimulai (Redesain Radikal: Cinematic Panels - Responsive Optimized) -->
    <section id="services" class="min-h-screen lg:h-[80vh] lg:min-h-[600px] bg-dark flex flex-col lg:flex-row overflow-hidden relative group/section">
        @foreach([
            ['id' => '01', 'slug' => 'service-pool', 'img' => 'pool.jpg', 'title' => 'Kolam Modern', 'desc' => 'Kolam renang minimalis dengan pemandangan terbuka.'],
            ['id' => '02', 'slug' => 'service-spa', 'img' => 'spa.jpg', 'title' => 'Spa & Relax', 'desc' => 'Pijat dan perawatan tubuh untuk kebugaran Anda.'],
            ['id' => '03', 'slug' => 'service-dining', 'img' => 'dining_hero.jpg', 'title' => 'Private Dining', 'desc' => 'Hidangan premium langsung di villa pribadi Anda.'],
            ['id' => '04', 'slug' => 'service-personal', 'img' => 'about_2.jpg', 'title' => 'Layanan Sigap', 'desc' => 'Staf sigap yang siap membantu kebutuhan Anda.']
        ] as $svc)
        <div id="{{ $svc['slug'] }}" class="relative flex-none h-64 sm:h-80 lg:h-full lg:flex-1 lg:hover:flex-[2.5] lg:target:flex-[2.5] transition-all duration-700 ease-in-out overflow-hidden border-b lg:border-b-0 lg:border-r border-white/5 group panel-target-fix">
            <img src="{{ asset('images/' . $svc['img']) }}" class="absolute inset-0 w-full h-full object-cover grayscale-[0.5] opacity-50 group-hover:opacity-80 group-hover:grayscale-0 group-hover:scale-110 group-[.lg\:target\:flex-\[2\.5\]]:opacity-80 group-[.lg\:target\:flex-\[2\.5\]]:grayscale-0 transition-all duration-[3000ms]" alt="{{ $svc['title'] }}">
            
            <!-- Readability Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-dark/95 via-dark/40 to-transparent transition-opacity duration-500"></div>
            
            <div class="absolute inset-0 p-8 sm:p-12 flex flex-col justify-end lg:justify-center">
                <span class="text-primary font-bold text-base sm:text-lg mb-2 lg:mb-4 block translate-y-4 group-hover:translate-y-0 group-[.lg\:target\:flex-\[2\.5\]]:translate-y-0 transition-transform">{{ $svc['id'] }}</span>
                <h3 class="text-xl sm:text-3xl lg:text-5xl font-bold text-white mb-2 lg:mb-6 tracking-tighter leading-[0.9] lg:leading-none truncate lg:whitespace-normal drop-shadow-lg">{{ $svc['title'] }}</h3>
                <div class="max-h-0 lg:group-hover:max-h-40 group-[.lg\:target\:flex-\[2\.5\]]:max-h-40 overflow-hidden transition-all duration-700 lg:opacity-0 lg:group-hover:opacity-100 group-[.lg\:target\:flex-\[2\.5\]]:opacity-100">
                    <p class="text-white text-sm sm:text-base lg:text-lg font-light leading-relaxed max-w-xs drop-shadow-md">
                        {{ $svc['desc'] }}
                    </p>
                </div>
                <!-- Mobile Only Desc -->
                <p class="text-white/80 text-xs sm:text-sm lg:hidden mb-4 opacity-100 line-clamp-2">
                    {{ $svc['desc'] }}
                </p>
            </div>
        </div>
        @endforeach
    </section>

    <!-- Nawala (Redesain Radikal: Manifesto Split - Responsive Optimized) -->
    <section id="philosophy" class="min-h-screen bg-white flex flex-col lg:flex-row overflow-hidden">
        <!-- Bagian Manifesto (Kiri) -->
        <div class="w-full lg:w-7/12 p-12 sm:p-20 lg:p-32 flex flex-col justify-center bg-light/30">
            <div class="max-w-xl animate__animated animate__fadeInLeft">
                <span class="text-xs font-bold uppercase tracking-[0.5em] text-primary mb-8 lg:mb-12 block">Filosofi Kami</span>
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tighter leading-[1] mb-8 lg:mb-12">
                    KAMI FOKUS <br> PADA <br> <span class="text-primary">KUALITAS.</span>
                </h2>
                <p class="text-xl sm:text-2xl font-light text-secondary leading-relaxed mb-8 lg:mb-12 italic">
                    "Kenyamanan yang sesungguhnya bukan dari apa yang terlihat, tapi dari seberapa tenang Anda saat berada di sini."
                </p>
                <div class="flex items-center gap-6">
                    <div class="w-12 h-px bg-dark"></div>
                    <span class="font-bold uppercase tracking-widest text-[10px] sm:text-xs">Manajemen Vireva</span>
                </div>
            </div>
        </div>

        <!-- Bagian Subscribe (Kanan) -->
        <div class="w-full lg:w-5/12 relative flex items-center justify-center p-8 sm:p-12 lg:p-12 min-h-[500px] lg:min-h-0 overflow-hidden">
            <!-- Background Image -->
            <img src="{{ asset('images/about_1.jpg') }}" class="absolute inset-0 w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-[5000ms]" alt="Join Background">
            <div class="absolute inset-0 bg-dark/50 backdrop-blur-[2px]"></div>

            <!-- Glass Card -->
            <div class="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-xl border border-white/20 p-8 sm:p-12 lg:p-16 shadow-2xl animate__animated animate__fadeInUp">
                <div class="space-y-8">
                    <div class="space-y-4">
                        <h3 class="text-2xl sm:text-3xl font-bold text-white tracking-tighter leading-tight">Tetap Terhubung.</h3>
                        <p class="text-white/70 font-light text-sm sm:text-base leading-relaxed">Dapatkan informasi terbaru mengenai koleksi villa dan penawaran spesial kami.</p>
                    </div>

                    <form class="space-y-6">
                        <div class="space-y-2">
                            <input type="email" placeholder="Alamat Email Anda" 
                                   class="w-full bg-white/5 border border-white/10 px-6 py-4 text-white text-sm outline-none focus:ring-1 focus:ring-primary focus:border-primary focus:bg-white/10 transition-all placeholder:text-white/20 rounded-none appearance-none">
                        </div>
                        <button class="w-full bg-white text-dark py-4 font-bold uppercase tracking-[0.2em] text-xs hover:bg-primary hover:text-white transition-all shadow-xl">
                            Gabung Sekarang
                        </button>
                    </form>

                    <div class="flex items-center gap-4 opacity-30">
                        <div class="h-px flex-1 bg-white"></div>
                        <span class="text-[8px] font-bold uppercase tracking-widest text-white">Vireva Exclusive</span>
                        <div class="h-px flex-1 bg-white"></div>
                    </div>
                </div>
            </div>

            <!-- Accent -->
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/20 rounded-full blur-3xl opacity-50 sm:opacity-100"></div>
        </div>
    </section>
@endsection
