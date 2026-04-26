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
                    <select name="year" onchange="this.form.submit()" class="bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl px-4 py-2 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        @for($i = date('Y'); $i >= 2023; $i--)
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
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-purple-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-purple-600/20">
                        <i data-lucide="home" class="w-6 h-6"></i>
                    </div>
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Unit Villa Aktif</div>
                    <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalVilla }} <span class="text-sm font-bold text-slate-400 ml-1 uppercase">Unit</span></div>
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
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#10b981',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 4,
                    pointRadius: 6,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value/1000000) + 'jt';
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
    </script>
    @endpush
</x-admin-layout>
