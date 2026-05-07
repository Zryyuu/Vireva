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
            
            // 1. Sedang Menginap (Tamu yang sudah check-in dan ada di dalam unit)
            $currentlyStaying = \App\Models\Pemesanan::where('status_pemesanan', 'aktif')->count();

            // 2. Check-in Terjadwal (Mendatang - Tamu yang akan datang di hari-hari berikutnya bulan ini)
            $upcomingCheckins = \App\Models\Pemesanan::where('status_pemesanan', 'menunggu')
                                ->where('tanggal_checkin', '>=', today())
                                ->whereMonth('tanggal_checkin', now()->month)
                                ->whereYear('tanggal_checkin', now()->year)
                                ->count();
            
            // 3. Performa Reservasi (Bulan Ini) - Total semua yang sukses/masuk data
            $monthlyTotal = \App\Models\Pemesanan::whereIn('status_pemesanan', ['menunggu', 'aktif', 'selesai'])
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();
            
            // Financial Metrics (Super Admin Only) - Filtered by Current Month
            $grossRevenue = \App\Models\Pemesanan::whereIn('status_pembayaran', ['settlement', 'paid'])
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->sum('total_biaya');
            
            $totalExpenses = \App\Models\Biaya::whereMonth('tanggal', now()->month)
                                ->whereYear('tanggal', now()->year)
                                ->sum('jumlah');
                                
            $netProfit = $grossRevenue - $totalExpenses;
                                
            $recentBookings = \App\Models\Pemesanan::with(['tamu.user', 'villa'])
                                ->orderBy('created_at', 'desc')
                                ->take(6)
                                ->get();

            // Chart Data: Monthly Revenue for Current Year
            $monthlyRevenue = \App\Models\Pemesanan::whereIn('status_pembayaran', ['settlement', 'paid'])
                ->whereYear('created_at', now()->year)
                ->get()
                ->groupBy(function($date) {
                    return \Carbon\Carbon::parse($date->created_at)->format('n');
                })
                ->map(function($month) {
                    return $month->sum('total_biaya');
                });

            // Chart Data: Monthly Expenses for Current Year
            $monthlyExpenses = \App\Models\Biaya::whereYear('tanggal', now()->year)
                ->get()
                ->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item->tanggal)->format('n');
                })
                ->map(function($month) {
                    return $month->sum('jumlah');
                });

            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $revenueData = [];
            $expenseData = [];
            for ($i = 1; $i <= 12; $i++) {
                $revenueData[] = $monthlyRevenue->get($i, 0);
                $expenseData[] = $monthlyExpenses->get($i, 0); // positif, 0 di bawah
            }

            // Calculate current occupancy rate
            // In the context of "Monitoring this month", we look at active stays
            $occupancyRate = $totalVillas > 0 ? ($currentlyStaying / $totalVillas) * 100 : 0;
        @endphp

        <!-- Dashboard Metrics Grid -->
        <div class="space-y-6">
            @if(Auth::user()->isSuperAdmin())
                <!-- Super Admin Financial Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-vireva.metric-card 
                        title="Pendapatan (Bulan Ini)" 
                        :value="$grossRevenue" 
                        icon="wallet" 
                        color="slate" 
                        subtitle="Pemasukan kotor lunas" 
                        isCurrency 
                    />

                    <x-vireva.metric-card 
                        title="Pengeluaran (Bulan Ini)" 
                        :value="$totalExpenses" 
                        icon="trending-down" 
                        color="red" 
                        subtitle="Operasional bulan ini" 
                        isCurrency 
                    />

                    <x-vireva.metric-card 
                        title="Laba Bersih (Bulan Ini)" 
                        :value="$netProfit" 
                        icon="activity" 
                        color="emerald" 
                        :subtitle="'Margin: ' . ($grossRevenue > 0 ? number_format(($netProfit / $grossRevenue) * 100, 1) : 0) . '%'" 
                        isCurrency 
                    />
                </div>
            @endif

            <!-- Common Operational Row (3 Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-vireva.metric-card 
                    title="Sedang Menginap" 
                    :value="$currentlyStaying" 
                    icon="door-closed" 
                    color="blue" 
                    subtitle="Unit terisi saat ini" 
                />

                <x-vireva.metric-card 
                    title="Check-in Terjadwal" 
                    :value="$upcomingCheckins" 
                    icon="calendar" 
                    color="amber" 
                    subtitle="Akan datang bulan ini" 
                />

                <x-vireva.metric-card 
                    title="Reservasi (Bulan Ini)" 
                    :value="$monthlyTotal" 
                    icon="bar-chart-3" 
                    color="emerald" 
                    subtitle="Total performa bulanan" 
                />
            </div>
        </div>

        @if(Auth::user()->isSuperAdmin())
        <!-- Performance Chart (Super Admin Only) -->
        <div class="bg-white border border-slate-200 p-8 rounded-[2rem] shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-bold text-lg text-slate-900">Tren Finansial ({{ now()->year }})</h3>
                    <p class="text-xs text-slate-500 font-medium">Performa pendapatan kotor bulanan di tahun berjalan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] font-bold text-slate-600 uppercase">Pendapatan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-[10px] font-bold text-slate-600 uppercase">Pengeluaran</span>
                    </div>
                </div>
            </div>
            <div class="h-[300px]">
                <canvas id="financialChart"></canvas>
            </div>
        </div>
        @endif

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
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest text-slate-500">Identitas Member</th>
                                <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest text-slate-500">Unit Terpilih</th>
                                <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest text-slate-500">Jadwal Check-In</th>
                                <th class="px-4 py-4 text-xs font-bold uppercase tracking-widest text-slate-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentBookings as $booking)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center font-bold text-sm text-emerald-600 flex-shrink-0">
                                                {{ substr($booking->tamu->nama_tamu ?? 'G', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors text-sm">{{ $booking->tamu->nama_tamu ?? 'Tamu' }}</div>
                                                <div class="text-xs text-slate-500 font-medium truncate max-w-[140px]">{{ $booking->tamu->user->email ?? $booking->tamu->no_hape ?? 'Tidak Ada Kontak' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="font-bold text-slate-900 text-sm">{{ $booking->villa->nama_villa ?? 'Tidak Diketahui' }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $booking->villa->tipe_villa ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-slate-900 font-bold text-sm">{{ $booking->tanggal_checkin->format('d M Y') }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $booking->total_hari }} Malam</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($booking->status_pemesanan === 'aktif')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Sedang Menginap
                                            </span>
                                        @elseif($booking->status_pemesanan === 'menunggu')
                                            @if($booking->status_pembayaran === 'settlement')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terkonfirmasi
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-orange-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Menunggu
                                                </span>
                                            @endif
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
                                    <div class="text-sm font-bold text-slate-900">Daftar Villa</div>
                                    <div class="text-xs text-slate-500 font-medium">Manajemen harga & unit</div>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 transition-colors"></i>
                        </a>
                        
                        @if(Auth::user()->isSuperAdmin())
                        <a href="{{ route('admin.laporan.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-transparent transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-600 transition-colors">
                                    <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">Laporan Finansial</div>
                                    <div class="text-xs text-slate-500 font-medium">Analisa pendapatan & performa</div>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 transition-colors"></i>
                        </a>
                        @endif
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
                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $occupancyRate }}%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-[10px] uppercase font-bold tracking-widest text-slate-400 relative z-10">
                        <span>Kapasitas</span>
                        <span>{{ number_format($occupancyRate, 0) }}% Terisi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->isSuperAdmin())
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('financialChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            const formatRp = (val) => {
                const abs = Math.abs(val);
                if (abs >= 1000000) return 'Rp ' + (abs/1000000).toLocaleString('id-ID', {minimumFractionDigits:0, maximumFractionDigits:3}) + ' jt';
                if (abs >= 1000) return 'Rp ' + (abs/1000).toLocaleString('id-ID') + ' rb';
                return 'Rp ' + abs.toLocaleString('id-ID');
            };

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Pendapatan',
                            data: {!! json_encode($revenueData) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            borderColor: '#059669',
                            borderWidth: 1.5,
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode($expenseData) !!},
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: '#dc2626',
                            borderWidth: 1.5,
                            borderRadius: 6,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + formatRp(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grace: '15%',
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                callback: function(value) {
                                    if (value === 0) return 'Rp 0';
                                    if (value >= 1000000) return 'Rp ' + (value/1000000).toLocaleString('id-ID') + 'jt';
                                    if (value >= 1000) return 'Rp ' + (value/1000).toLocaleString('id-ID') + 'rb';
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                },
                                font: { size: 10, weight: 'bold' }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: 'bold' } }
                        }
                    }
                }
            });
        });

        // Auto-hide alerts after 3 seconds
        setTimeout(() => {
            ['alert-success', 'alert-error'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-20px)';
                    setTimeout(() => el.remove(), 500);
                }
            });
        }, 3000);
    </script>
    @endpush
    @endif
</x-admin-layout>
