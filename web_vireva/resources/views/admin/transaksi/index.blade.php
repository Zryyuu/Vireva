<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Manajemen Transaksi</h1>
                <p class="text-sm text-slate-500 font-medium">Pantau arus kas pemesanan, tagihan, dan pengembalian dana tamu.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.transaksi.index') }}" class="{{ $statusFilter == 'semua' ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }} px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors">
                    Semua
                </a>
                <a href="{{ route('admin.transaksi.index', ['status' => 'menunggu']) }}" class="{{ $statusFilter == 'menunggu' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }} px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors">
                    Menunggu
                </a>
                <a href="{{ route('admin.transaksi.index', ['status' => 'aktif']) }}" class="{{ $statusFilter == 'aktif' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }} px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors">
                    Aktif
                </a>
            </div>
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
                            <th scope="col" class="px-6 py-4 font-bold text-right">Aksi Manual</th>
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
                                <div class="font-bold text-slate-900 mb-1.5">{{ $trx->formatted_biaya }}</div>
                                
                                @if($trx->status_pemesanan == 'aktif')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-widest">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Lunas & Aktif
                                    </span>
                                @elseif($trx->status_pemesanan == 'menunggu')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-100 text-amber-700 uppercase tracking-widest">
                                        <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div> Menunggu Bayar
                                    </span>
                                @elseif($trx->status_pemesanan == 'batal')
                                    @if($trx->pembayaran && $trx->pembayaran->status_bayar == 'refund')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-widest">
                                            <i data-lucide="refresh-ccw" class="w-3 h-3"></i> Dikembalikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-100 text-red-700 uppercase tracking-widest">
                                            <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Batal
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 uppercase tracking-widest">
                                        <i data-lucide="check" class="w-3 h-3"></i> Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                @if($trx->status_pemesanan == 'menunggu')
                                <form action="{{ route('admin.transaksi.action', $trx->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Setujui pembayaran dan aktifkan reservasi?');">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="px-3 py-1.5 text-[10px] font-bold bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg transition-all shadow-sm shadow-emerald-500/20">
                                        Setujui
                                    </button>
                                </form>
                                @endif

                                @if($trx->status_pemesanan == 'aktif' && $trx->villa && $trx->villa->status_villa !== 'terisi')
                                <form action="{{ route('admin.transaksi.action', $trx->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="action" value="checkin">
                                    <button type="submit" class="px-3 py-1.5 text-[10px] font-bold bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-all shadow-sm shadow-blue-500/20">
                                        Check-In
                                    </button>
                                </form>
                                @endif

                                @if($trx->status_pemesanan == 'aktif' && $trx->villa && $trx->villa->status_villa === 'terisi')
                                <form action="{{ route('admin.transaksi.action', $trx->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="action" value="checkout">
                                    <button type="submit" class="px-3 py-1.5 text-[10px] font-bold bg-slate-800 text-white hover:bg-slate-900 rounded-lg transition-all shadow-sm">
                                        Check-Out
                                    </button>
                                </form>
                                @endif

                                @if(in_array($trx->status_pemesanan, ['menunggu', 'aktif']))
                                <form action="{{ route('admin.transaksi.action', $trx->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?');">
                                    @csrf
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit" class="px-3 py-1.5 text-[10px] font-bold bg-white border border-red-200 text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                        Batal
                                    </button>
                                </form>
                                @endif
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
