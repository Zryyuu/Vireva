<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate__animated animate__fadeIn">
        
        <?php
            // Fetch User's Guest Profile and Bookings
            $tamu = Auth::user()->tamu;
            $myBookings = collect();
            
            if ($tamu) {
                $myBookings = \App\Models\Pemesanan::with('kamar')
                                ->where('tamu_id', $tamu->id)
                                ->orderBy('created_at', 'desc')
                                ->get();
            }
        ?>

        <!-- Welcome Header -->
        <div class="relative overflow-hidden rounded-3xl bg-emerald-50 border border-emerald-100 p-8 md:p-12 shadow-sm">
            <div class="relative z-10">
                <div class="text-emerald-700 font-bold tracking-widest uppercase text-xs mb-2">Member Portal</div>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4 text-slate-900">
                    Selamat Datang, <span class="text-emerald-600">{{ Auth::user()->name }}</span>
                </h1>
                <p class="text-slate-600 max-w-xl leading-relaxed font-medium">
                    Temukan kenyamanan eksklusif dan rencanakan liburan impian Anda bersama kami. Kelola privasi dan reservasi villa Anda dengan mudah dari dasbor ini.
                </p>
            </div>
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-200/40 rounded-full blur-[100px] pointer-events-none"></div>
        </div>

        @if(!$tamu)
            <!-- Complete Profile Prompt -->
            <div class="bg-orange-50 p-8 rounded-3xl border border-orange-200 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                <div class="flex items-center gap-4 border-l-4 border-orange-500 pl-4">
                    <div class="p-3 bg-orange-100 rounded-full text-orange-600 hidden md:block">
                        <i data-lucide="user-plus" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-900 mb-1">Lengkapi Profil Anda</h3>
                        <p class="text-sm text-slate-600 font-medium">Kami butuh detail kontak Anda untuk memudahkan proses reservasi villa.</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-colors whitespace-nowrap shadow-md">Lengkapi Sekarang</a>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Active Reservations / History -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Promo Action -->
                <div class="bg-white p-8 rounded-3xl relative group overflow-hidden border border-slate-200 hover:border-emerald-300 transition-colors shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h3 class="text-2xl font-bold mb-2 text-slate-900">Eksplorasi Keindahan</h3>
                            <p class="text-slate-500 text-sm mb-6 max-w-md font-medium">Katalog villa mewah kami menanti sejarah liburan Anda. Dapatkan penawaran khusus member hari ini.</p>
                            <a href="{{ route('bookings.explore') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all transform hover:-translate-y-1 shadow-lg">
                                <i data-lucide="search" class="w-4 h-4"></i> Cari Villa
                            </a>
                        </div>
                        <div class="hidden md:block">
                            <i data-lucide="compass" class="w-24 h-24 text-emerald-100 group-hover:scale-110 transition-transform duration-500"></i>
                        </div>
                    </div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-[80px]"></div>
                </div>

                <!-- Bookings List -->
                <h3 class="font-bold text-lg mt-8 mb-4 border-b border-slate-200 pb-4 text-slate-900">Riwayat Perjalanan Anda</h3>
                
                @if($myBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($myBookings as $booking)
                            <div class="bg-white p-6 rounded-2xl border border-slate-200 hover:shadow-md transition-shadow flex flex-col sm:flex-row justify-between gap-6">
                                <div class="flex gap-6">
                                    <!-- Date Cube -->
                                    <div class="w-20 h-20 rounded-2xl bg-slate-50 flex flex-col items-center justify-center border border-slate-100 shrink-0">
                                        <span class="text-xs text-slate-500 uppercase font-bold">{{ $booking->tanggal_checkin->format('M') }}</span>
                                        <span class="text-2xl font-extrabold text-emerald-600">{{ $booking->tanggal_checkin->format('d') }}</span>
                                    </div>
                                    
                                    <!-- Details -->
                                    <div class="flex flex-col justify-center">
                                        <h4 class="font-bold text-lg text-slate-900 mb-1">Villa {{ $booking->kamar->tipe_kamar ?? 'Luxury' }}</h4>
                                        <p class="text-sm text-slate-500 mb-3 font-medium">Unit #{{ $booking->kamar->nomor_kamar ?? '-' }} • {{ $booking->total_hari }} Malam</p>
                                        
                                        <div>
                                            @if($booking->status_pemesanan === 'aktif')
                                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-blue-200">Check In</span>
                                            @elseif($booking->status_pemesanan === 'menunggu')
                                                <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-orange-200">Menunggu Pembayaran</span>
                                            @elseif($booking->status_pemesanan === 'selesai')
                                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-emerald-200">Selesai</span>
                                            @else
                                                <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-red-200">Dibatalkan</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex sm:flex-col justify-between sm:justify-center items-end gap-2">
                                    <div class="text-right">
                                        <div class="text-xs text-slate-400 uppercase tracking-widest font-bold">Total Tagihan</div>
                                        <div class="font-bold text-slate-900 text-lg">Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}</div>
                                    </div>
                                    <a href="#" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 uppercase tracking-widest transition-colors mt-2">Detail <i data-lucide="arrow-right" class="inline w-3 h-3"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white flex flex-col items-center justify-center p-12 text-center rounded-3xl border border-slate-200 border-dashed">
                        <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mb-6 text-slate-300">
                            <i data-lucide="map" class="w-10 h-10"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-2 text-slate-900">Belum Ada Perjalanan Terjadwal</h4>
                        <p class="text-slate-500 text-sm max-w-sm mb-6 font-medium">Waktunya merencanakan ketenangan. Temukan villa terbaik kami dan buat kenangan berharga.</p>
                        <button class="px-6 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-sm rounded-xl transition-colors border border-emerald-200">Lihat Koleksi Villa</button>
                    </div>
                @endif
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Loyalty Card Mockup (Still dark but premium, representing a physical black card) -->
                <div class="h-48 rounded-3xl overflow-hidden relative border border-slate-800 shadow-2xl group cursor-pointer hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.5)] transition-all">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#1A1A1A] to-[#0A0A0A] group-hover:scale-105 transition-transform duration-700"></div>
                    <!-- Card Pattern -->
                    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 10px 10px, white 1px, transparent 0); background-size: 30px 30px;"></div>
                    
                    <div class="relative h-full p-6 flex flex-col justify-between z-10">
                        <div class="flex justify-between items-start">
                            <div class="font-bold tracking-widest uppercase text-white">Vireva<span class="text-emerald-400">.</span> Black</div>
                            <i data-lucide="gem" class="w-5 h-5 text-emerald-400"></i>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400 tracking-widest uppercase mb-1">Member Privilege</div>
                            <div class="font-mono text-sm tracking-[0.2em] text-white/90">
                                4839 2010 **** ****
                            </div>
                            <div class="mt-2 text-[10px] text-emerald-400 font-bold">Status: Aktif</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <h4 class="font-bold text-sm mb-4 uppercase tracking-widest text-slate-400">Layanan Eksklusif</h4>
                    <div class="space-y-2">
                        <a href="#" class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                                <i data-lucide="chef-hat" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900">Private Dining</div>
                                <div class="text-xs text-slate-500 font-medium">Pesan koki di villa</div>
                            </div>
                        </a>
                        <a href="#" class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <i data-lucide="car" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900">Airport Transfer</div>
                                <div class="text-xs text-slate-500 font-medium">Layanan penjemputan</div>
                            </div>
                        </a>
                        <a href="#" class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <i data-lucide="message-square" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900">Concierge 24/7</div>
                                <div class="text-xs text-slate-500 font-medium">Hubungi petugas</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
