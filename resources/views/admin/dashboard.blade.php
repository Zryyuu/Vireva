<x-admin-layout>
    <!-- Admin Dashboard Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate__animated animate__fadeIn">
        
        <!-- Premium Welcome Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-white border border-slate-200 p-8 md:p-12 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="text-emerald-700 font-bold tracking-widest uppercase text-xs mb-2">Vireva Management Center</div>
                    <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4 text-slate-900">
                        Performa <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Villa Anda.</span>
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base max-w-2xl leading-relaxed font-medium">
                        Pantau operasional harian, manajemen pemesanan secara real-time, dan evaluasi pendapatan dalam satu dasbor eksklusif. Selamat bertugas, {{ Auth::user()->name }}.
                    </p>
                </div>
                <!-- Animated decorative element -->
                <div class="hidden lg:flex relative w-32 h-32 items-center justify-center">
                    <div class="absolute inset-0 border-2 border-dashed border-emerald-200 rounded-full animate-[spin_20s_linear_infinite]"></div>
                    <div class="absolute inset-2 border-2 border-emerald-100 rounded-full animate-[spin_15s_linear_infinite_reverse]"></div>
                    <i data-lucide="shield-check" class="w-10 h-10 text-emerald-600"></i>
                </div>
            </div>
            
            <!-- Lighting effect -->
            <div class="absolute -right-32 -top-32 w-96 h-96 bg-emerald-50 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute -left-32 -bottom-32 w-96 h-96 bg-blue-50 rounded-full blur-[120px] pointer-events-none"></div>
        </div>

        @php
            // Execute real queries for the dashboard metrics
            $totalVillas = \App\Models\Villa::count();
            $activeBookings = \App\Models\Pemesanan::whereIn('status_pemesanan', ['menunggu', 'aktif'])->count();
            $totalRevenue = \App\Models\Pemesanan::where('status_pemesanan', 'selesai')->sum('total_biaya');
            
            // Guests currently active (checked in)
            $activeGuests = \App\Models\Pemesanan::where('status_pemesanan', 'aktif')
                                ->distinct('tamu_id')
                                ->count('tamu_id');
                                
            $recentBookings = \App\Models\Pemesanan::with(['tamu.user', 'villa'])
                                ->orderBy('created_at', 'desc')
                                ->take(6)
                                ->get();
        @endphp

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Metric 1 -->
            <div class="bg-white border border-slate-200 p-6 rounded-3xl relative overflow-hidden group hover:border-emerald-300 shadow-sm transition-all duration-500 hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500 mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="home" class="w-6 h-6"></i>
                </div>
                <div class="text-4xl font-extrabold tracking-tight mb-2 text-slate-900">{{ $totalVillas }}</div>
                <div class="text-sm font-bold text-slate-500 tracking-wider uppercase">Unit Villa</div>
                <div class="absolute right-0 bottom-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="home" class="w-24 h-24 text-slate-900"></i>
                </div>
            </div>

            <!-- Metric 2 -->
            <div class="bg-white border border-slate-200 p-6 rounded-3xl relative overflow-hidden group hover:border-emerald-300 shadow-sm transition-all duration-500 hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="calendar-check-2" class="w-6 h-6"></i>
                </div>
                <div class="text-4xl font-extrabold tracking-tight mb-2 text-slate-900">{{ $activeBookings }}</div>
                <div class="text-sm font-bold text-slate-500 tracking-wider uppercase">Booking Aktif</div>
                <div class="absolute right-0 bottom-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="calendar-check-2" class="w-24 h-24 text-slate-900"></i>
                </div>
            </div>

            <!-- Metric 3 -->
            <div class="bg-white border border-slate-200 p-6 rounded-3xl relative overflow-hidden group hover:border-emerald-300 shadow-sm transition-all duration-500 hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
                <div class="text-4xl font-extrabold tracking-tight mb-2 text-slate-900">
                    <span class="text-xl text-slate-400 mr-1">Rp</span>{{ number_format($totalRevenue / 1000000, 1, ',', '.') }}<span class="text-xl text-slate-400 ml-1">Jt</span>
                </div>
                <div class="text-sm font-bold text-slate-500 tracking-wider uppercase">Total Pendapatan</div>
                <div class="absolute right-0 bottom-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="wallet" class="w-24 h-24 text-slate-900"></i>
                </div>
            </div>

            <!-- Metric 4 -->
            <div class="bg-white border border-slate-200 p-6 rounded-3xl relative overflow-hidden group hover:border-emerald-300 shadow-sm transition-all duration-500 hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div class="text-4xl font-extrabold tracking-tight mb-2 text-slate-900">{{ $activeGuests }}</div>
                <div class="text-sm font-bold text-slate-500 tracking-wider uppercase">Tamu Terdaftar</div>
                <div class="absolute right-0 bottom-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i data-lucide="users" class="w-24 h-24 text-slate-900"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Reservation Table Section -->
            <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-200 flex flex-col overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-lg text-slate-900">Aktivitas Reservasi</h3>
                    </div>
                    <a href="{{ route('admin.transaksi.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold uppercase tracking-widest text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">Kelola Semua</a>
                </div>
                
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-slate-500">Identitas Member</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-slate-500">Unit Terpilih</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-slate-500">Jadwal Check-In</th>
                                <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-slate-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentBookings as $booking)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center font-bold text-sm text-emerald-600">
                                                {{ substr($booking->tamu->nama_tamu ?? 'G', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $booking->tamu->nama_tamu ?? 'Guest' }}</div>
                                                <div class="text-xs text-slate-500 font-medium">{{ $booking->tamu->user->email ?? $booking->tamu->no_hape ?? 'No Contact' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="font-bold text-slate-900">{{ $booking->villa->nama_villa ?? 'Unknown' }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $booking->villa->tipe_villa ?? '-' }}</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="text-slate-900 font-bold">{{ $booking->tanggal_checkin->format('d M Y') }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $booking->total_hari }} Malam</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        @if($booking->status_pemesanan === 'aktif')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Checked In
                                            </span>
                                        @elseif($booking->status_pemesanan === 'menunggu')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-orange-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Terjadwal
                                            </span>
                                        @elseif($booking->status_pemesanan === 'selesai')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Batal
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="inbox" class="w-12 h-12 mb-3 text-slate-200"></i>
                                            <p class="font-medium">Belum ada data reservasi saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions Sidebar -->
            <div class="space-y-6">
                <!-- Administrative Actions Widget -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-50 rounded-bl-[100px] pointer-events-none"></div>
                    
                    <h3 class="font-bold text-lg mb-6 flex items-center gap-3 text-slate-900">
                        <i data-lucide="zap" class="text-emerald-600 w-5 h-5"></i> Aksi Cepat
                    </h3>
                    
                    <div class="space-y-3 relative z-10">
                        <a href="{{ route('admin.villa.create') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 border border-transparent transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-600 group-hover:text-emerald-600 transition-colors">
                                    <i data-lucide="plus" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">Tambah Villa</div>
                                    <div class="text-xs text-slate-500 font-medium">Listing villa baru</div>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all"></i>
                        </a>

                        <a href="{{ route('admin.villa.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-transparent transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-600 transition-colors">
                                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">Katalog Villa</div>
                                    <div class="text-xs text-slate-500 font-medium">Manajemen harga & unit</div>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 transition-colors"></i>
                        </a>
                        
                        <a href="#" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-transparent transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-600 transition-colors">
                                    <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">Laporan Keuangan</div>
                                    <div class="text-xs text-slate-500 font-medium">Unduh mutasi bulan ini</div>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 transition-colors"></i>
                        </a>
                    </div>
                </div>

                <!-- Villa Info Snippet -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 p-8 rounded-3xl border border-slate-700 shadow-lg relative overflow-hidden">
                    <i data-lucide="info" class="absolute top-6 right-6 text-white/10 w-16 h-16"></i>
                    <h4 class="font-bold text-sm mb-2 uppercase tracking-widest text-emerald-400 relative z-10">Sistem Monitoring</h4>
                    <p class="text-xs text-slate-300 leading-relaxed mb-6 font-medium relative z-10">
                        Pembaruan otomatis mengenai status villa dan layanan reservasi. Evaluasi data ini untuk menentukan harga dinamis (Dynamic Pricing) di musim liburan.
                    </p>
                    <div class="w-full h-1 bg-slate-700 rounded-full overflow-hidden relative z-10">
                        <div class="h-full bg-emerald-500 w-2/3 rounded-full"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-[10px] uppercase font-bold tracking-widest text-slate-400 relative z-10">
                        <span>Kapasitas</span>
                        <span>65% Terisi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
