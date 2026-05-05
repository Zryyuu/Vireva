<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate__animated animate__fadeIn">
        
        <?php
            // Fetch User's Guest Profile and Bookings
            $tamu = Auth::user()->tamu;
            $myBookings = collect();
            
            if ($tamu) {
                // Real Data Fetching
                $myBookings = \App\Models\Pemesanan::with('villa')
                                ->where('tamu_id', $tamu->id)
                                ->orderBy('created_at', 'desc')
                                ->get();
                
                $totalActive = $myBookings->whereIn('status_pemesanan', ['aktif', 'menunggu'])->count();
                $totalSelesai = $myBookings->where('status_pemesanan', 'selesai')->count();
                $totalSpending = $myBookings->where('status_pemesanan', 'selesai')->sum('total_biaya');
            }
        ?>

        <!-- Welcome Header -->
        <div class="relative overflow-hidden rounded-3xl bg-emerald-50 border border-emerald-100 p-8 md:p-12 shadow-sm">
            <div class="relative z-10">
                <div class="text-emerald-700 font-bold tracking-widest uppercase text-xs mb-2">Portal Member</div>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4 text-slate-900">
                    Selamat Datang, <span class="text-emerald-600">{{ Auth::user()->name }}</span>
                </h1>
                <p class="text-slate-600 max-w-xl leading-relaxed font-medium">
                    Temukan kenyamanan eksklusif dan rencanakan liburan impian Anda bersama kami. Kelola privasi dan reservasi villa Anda dengan mudah dari dasbor ini.
                </p>
            </div>
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-200/40 rounded-full blur-[100px] pointer-events-none"></div>
        </div>

        @if(!$tamu || !$tamu->no_hape || !$tamu->no_identitas || !$tamu->alamat)
            <!-- Complete Profile Prompt -->
            <div class="bg-orange-50 p-8 rounded-3xl border border-orange-200 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                <div class="flex items-center gap-4 border-l-4 border-orange-500 pl-4">
                    <div class="p-3 bg-orange-100 rounded-full text-orange-600 hidden md:block">
                        <i data-lucide="user-plus" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-900 mb-1">Profil Belum Lengkap</h3>
                        <p class="text-sm text-slate-600 font-medium">Mohon lengkapi No. WhatsApp, KTP, dan Alamat Anda untuk kemudahan proses reservasi villa.</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl transition-colors whitespace-nowrap shadow-md">Lengkapi Sekarang</a>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Reservasi</div>
                <div class="text-2xl font-black text-slate-900">{{ $myBookings->count() }}</div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Booking Aktif</div>
                <div class="text-2xl font-black text-emerald-600">{{ $totalActive ?? 0 }}</div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Selesai Menginap</div>
                <div class="text-2xl font-black text-slate-900">{{ $totalSelesai ?? 0 }}</div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Bookings List -->
            <h3 class="font-bold text-lg mt-8 mb-4 border-b border-slate-200 pb-4 text-slate-900">Riwayat Perjalanan Anda</h3>
            
            @if($myBookings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($myBookings as $booking)
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 hover:shadow-md transition-shadow flex flex-col justify-between gap-6">
                            <div class="flex gap-6">
                                <!-- Date Cube -->
                                <div class="w-20 h-20 rounded-2xl bg-slate-50 flex flex-col items-center justify-center border border-slate-100 shrink-0">
                                    <span class="text-xs text-slate-500 uppercase font-bold">{{ $booking->tanggal_checkin->format('M') }}</span>
                                    <span class="text-2xl font-extrabold text-emerald-600">{{ $booking->tanggal_checkin->format('d') }}</span>
                                </div>
                                
                                <!-- Details -->
                                <div class="flex flex-col justify-center">
                                    <h4 class="font-bold text-lg text-slate-900 mb-1">Villa {{ $booking->villa->nama_villa ?? 'Luxury' }}</h4>
                                    <p class="text-sm text-slate-500 mb-3 font-medium">{{ $booking->total_hari }} Malam • {{ $booking->tanggal_checkin->format('d M') }} - {{ $booking->tanggal_checkout->format('d M Y') }}</p>
                                    
                                    <div>
                                        @if($booking->status_pemesanan === 'aktif')
                                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-blue-200">Aktif / Check In</span>
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
                            <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Total Tagihan</div>
                                    <div class="font-bold text-slate-900 text-lg">Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}</div>
                                </div>
                                <a href="{{ route('bookings.show', $booking->id) }}" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-xs font-bold text-slate-700 uppercase tracking-widest rounded-xl transition-all border border-slate-200">Detail Reservasi</a>
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
                    <a href="{{ route('bookings.explore') }}" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-colors shadow-md">Eksplorasi Villa Sekarang</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

