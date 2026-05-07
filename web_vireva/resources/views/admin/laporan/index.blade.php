<x-admin-layout>
    <div class="space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Laporan Finansial & Performa</h1>
                <p class="text-sm text-slate-500 font-medium">Analisa mendalam mengenai arus kas dan tingkat okupansi properti Anda.</p>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex gap-2">
                    <select name="month" onchange="this.form.submit()" class="form-select bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl px-4 py-2.5 shadow-sm min-w-[140px]">
                        <option value="">Semua Bulan</option>
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $idx => $m)
                            <option value="{{ $idx + 1 }}" {{ $month == ($idx + 1) ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                    <select name="year" onchange="this.form.submit()" class="form-select bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl px-4 py-2.5 shadow-sm min-w-[120px]">
                        @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>Tahun {{ $i }}</option>
                        @endfor
                    </select>
                </form>
                <button onclick="window.print()" class="bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-900 transition-all flex items-center gap-2 shadow-lg shadow-slate-900/20">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </div>

        <!-- Summary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-emerald-600/20">
                        <i data-lucide="wallet" class="w-6 h-6"></i>
                    </div>
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Pendapatan (Gross)</div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-blue-600/20">
                        <i data-lucide="calendar-check" class="w-6 h-6"></i>
                    </div>
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Reservasi</div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalBooking }} <span class="text-sm font-bold text-slate-400 ml-1 uppercase">Transaksi</span></div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-red-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-red-600/20">
                        <i data-lucide="trending-down" class="w-6 h-6"></i>
                    </div>
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Pengeluaran</div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Chart -->
            <div class="lg:col-span-2 bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-bold text-lg text-slate-900">Grafik Pendapatan Bulanan ({{ $year }})</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Revenue</span>
                    </div>
                </div>
                <div class="h-[350px]">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Popular Villas -->
            <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm">
                <h3 class="font-bold text-lg text-slate-900 mb-6">Villa Terpopuler</h3>
                <div class="space-y-6">
                    @foreach($popularVillas as $index => $villa)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 font-black text-sm group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $villa->nama_villa }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $villa->tipe_villa }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-black text-emerald-600">{{ $villa->pemesanan_count }}x</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Dipesan</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12 p-6 bg-slate-900 rounded-3xl relative overflow-hidden">
                    <i data-lucide="award" class="absolute -right-4 -bottom-4 w-20 h-20 text-white/5"></i>
                    <h4 class="text-emerald-400 font-bold text-xs uppercase tracking-widest mb-2">Insight Bisnis</h4>
                    <p class="text-white/70 text-[11px] leading-relaxed font-medium">
                        Villa <strong>{{ $popularVillas->first()->nama_villa ?? '-' }}</strong> adalah aset paling produktif tahun ini. Pertimbangkan untuk menaikkan harga atau memberikan promo bundle.
                    </p>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-lg text-slate-900">Arus Kas Terbaru</h3>
                <a href="{{ route('admin.transaksi.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 uppercase tracking-widest">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tanggal</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Customer</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Villa</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recentTransactions as $trx)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-4 text-xs font-bold text-slate-600">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-8 py-4">
                                    <div class="text-sm font-bold text-slate-900">{{ $trx->tamu->nama_tamu ?? 'Tamu' }}</div>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="text-xs font-bold text-slate-700">{{ $trx->villa->nama_villa ?? '-' }}</div>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <div class="text-sm font-black text-slate-900">Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}</div>
                                    <div class="mt-1">
                                        @if($trx->status_pembayaran == 'settlement')
                                            <span class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">Lunas</span>
                                        @elseif($trx->status_pembayaran == 'cancel')
                                            <span class="text-[9px] font-bold text-red-600 uppercase tracking-widest bg-red-50 px-2 py-0.5 rounded-md border border-red-100">Batal</span>
                                        @else
                                            <span class="text-[9px] font-bold text-amber-600 uppercase tracking-widest bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100">Pending</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('revenueChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            const formatRp = (val) => {
                const abs = Math.abs(val);
                if (abs >= 1000000) return 'Rp ' + (abs/1000000).toLocaleString('id-ID', {minimumFractionDigits:0, maximumFractionDigits:3}) + ' jt';
                return 'Rp ' + abs.toLocaleString('id-ID');
            };

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [
                        {
                            label: 'Pendapatan',
                            data: {!! json_encode($chartData) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            borderColor: '#059669',
                            borderWidth: 1.5,
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode($expenseChartData) !!},
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
                    interaction: {
                        mode: 'index',
                        intersect: false
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
                                font: { size: 11, weight: 'bold' }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11, weight: 'bold' } }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
