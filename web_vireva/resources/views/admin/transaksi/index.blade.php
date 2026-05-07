<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Manajemen Transaksi</h1>
                <p class="text-sm text-slate-500 font-medium">Pantau arus kas pemesanan, tagihan, dan pengembalian dana tamu.</p>
            </div>
            <form action="{{ route('admin.transaksi.index') }}" method="GET" class="flex gap-2">
                <select name="month" onchange="this.form.submit()" class="form-select bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-xl px-4 py-2.5 shadow-sm min-w-[120px]">
                    @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $idx => $m)
                        <option value="{{ $idx + 1 }}" {{ $month == ($idx + 1) ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
                <select name="year" onchange="this.form.submit()" class="form-select bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-xl px-4 py-2.5 shadow-sm min-w-[110px]">
                    @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>Tahun {{ $i }}</option>
                    @endfor
                </select>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 p-4 rounded-2xl border-l-4 border-emerald-500 flex items-center gap-3 shadow-sm">
                <div class="p-2 bg-white rounded-full text-emerald-600 shadow-sm border border-emerald-100">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div class="text-sm font-bold text-emerald-700">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 p-4 rounded-2xl border-l-4 border-red-500 flex items-center gap-3 shadow-sm">
                <div class="p-2 bg-white rounded-full text-red-600 shadow-sm border border-red-100">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
                <div class="text-sm font-bold text-red-700">{{ session('error') }}</div>
            </div>
        @endif

        <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 bg-slate-50 uppercase tracking-widest border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">Kode & Tamu</th>
                            <th scope="col" class="px-6 py-4 font-bold">Unit Villa</th>
                            <th scope="col" class="px-6 py-4 font-bold">Jadwal Menginap</th>
                            <th scope="col" class="px-6 py-4 font-bold">Status Tagihan</th>
                            <th scope="col" class="px-6 py-4 font-bold">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transaksi as $trx)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-black text-slate-900 tracking-tight text-base">#TRX-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-slate-500 text-xs mt-0.5 font-medium flex items-center gap-1">
                                    <i data-lucide="user" class="w-3 h-3"></i> 
                                    {{ $trx->tamu ? $trx->tamu->nama_tamu : ($trx->user ? $trx->user->name : 'Tamu') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700">{{ $trx->villa ? $trx->villa->nama_villa : '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $trx->villa ? $trx->villa->tipe_villa : '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-700 font-medium whitespace-nowrap">
                                    {{ $trx->tanggal_checkin ? $trx->tanggal_checkin->format('d M') : '-' }} <span class="text-slate-300 mx-1">→</span> {{ $trx->tanggal_checkout ? $trx->tanggal_checkout->format('d M') : '-' }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1">{{ $trx->total_hari }} Malam</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($trx->status_pemesanan == 'aktif')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-widest">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> Sedang Menginap
                                    </span>
                                @elseif($trx->status_pemesanan == 'menunggu')
                                    @if($trx->status_pembayaran == 'settlement')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-widest">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Lunas / Terkonfirmasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-100 text-amber-700 uppercase tracking-widest">
                                            <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div> Menunggu Bayar
                                        </span>
                                    @endif
                                @elseif($trx->status_pemesanan == 'selesai')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 uppercase tracking-widest">
                                        <i data-lucide="check" class="w-3 h-3"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-100 text-red-700 uppercase tracking-widest">
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Batal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $trx->formatted_biaya }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $trx->total_hari }} malam</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-4">
                                        <i data-lucide="receipt" class="w-8 h-8 text-slate-300"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600">Tidak ada bukti transaksi ditemukan.</p>
                                    <p class="text-xs mt-1">Harap menunggu hingga tamu melakukan reservasi baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transaksi->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-white">
                {{ $transaksi->links() }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>
