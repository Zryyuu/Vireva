<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Manajemen <span class="text-emerald-600">Reservasi</span></h1>
                <p class="text-sm text-slate-500 font-medium">Pantau jadwal menginap tamu dan kelola status check-in/out.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reservasi.index', ['status' => 'semua']) }}" class="px-4 py-2 rounded-xl text-sm font-bold {{ $statusFilter == 'semua' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200' }} transition-all">
                    Semua
                </a>
                <a href="{{ route('admin.reservasi.index', ['status' => 'aktif']) }}" class="px-4 py-2 rounded-xl text-sm font-bold {{ $statusFilter == 'aktif' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200' }} transition-all">
                    Aktif
                </a>
                <a href="{{ route('admin.reservasi.index', ['status' => 'selesai']) }}" class="px-4 py-2 rounded-xl text-sm font-bold {{ $statusFilter == 'selesai' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200' }} transition-all">
                    Selesai
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

        <!-- Reservation Table Card -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Info Tamu</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Unit Villa</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500 text-center">Jadwal Menginap</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500 text-center">Status</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reservasi as $res)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-white font-black shrink-0 shadow-sm">
                                            {{ substr($res->tamu->nama_tamu ?? 'G', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $res->tamu->nama_tamu ?? 'Tamu' }}</div>
                                            <div class="text-xs text-slate-400 font-medium tracking-tight">ID: #VRV-{{ str_pad($res->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-900">{{ $res->villa->nama_villa ?? 'Tidak Diketahui' }}</div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $res->villa->tipe_villa ?? '-' }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="text-center">
                                                <div class="text-[9px] uppercase font-bold text-slate-400 tracking-tighter">In</div>
                                                <div class="text-xs font-black text-slate-900">{{ $res->tanggal_checkin->format('d M') }}</div>
                                            </div>
                                            <div class="w-8 h-[2px] bg-slate-100 relative">
                                                <div class="absolute -right-1 -top-[3px] w-1.5 h-1.5 border-t-2 border-r-2 border-slate-200 rotate-45"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-[9px] uppercase font-bold text-slate-400 tracking-tighter">Out</div>
                                                <div class="text-xs font-black text-slate-900">{{ $res->tanggal_checkout->format('d M') }}</div>
                                            </div>
                                        </div>
                                        <div class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full mt-1 border border-emerald-100 uppercase tracking-tighter">
                                            {{ $res->total_hari }} Malam
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($res->status_pemesanan == 'menunggu')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-orange-200">
                                            <div class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></div> Menunggu
                                        </span>
                                    @elseif($res->status_pemesanan == 'aktif')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-blue-200">
                                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div> Aktif / In-Stay
                                        </span>
                                    @elseif($res->status_pemesanan == 'selesai')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-emerald-200">
                                            <i data-lucide="check" class="w-3 h-3"></i> Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-red-200">
                                            <i data-lucide="x" class="w-3 h-3"></i> Batal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($res->status_pemesanan == 'aktif')
                                            <form action="{{ route('admin.transaksi.action', $res->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="checkout">
                                                <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-md group">
                                                    <i data-lucide="log-out" class="w-3 h-3"></i> Checkout
                                                </button>
                                            </form>
                                        @elseif($res->status_pemesanan == 'menunggu' && $res->status_pembayaran == 'settlement')
                                            <form action="{{ route('admin.transaksi.action', $res->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="checkin">
                                                <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-md shadow-emerald-500/20 group">
                                                    <i data-lucide="log-in" class="w-3 h-3"></i> Check-in
                                                </button>
                                            </form>
                                        @elseif($res->status_pemesanan == 'menunggu' && $res->status_pembayaran == 'pending')
                                             <form action="{{ route('admin.transaksi.action', $res->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-md group" title="Tandai Sudah Dibayar Manual">
                                                    <i data-lucide="banknote" class="w-3 h-3"></i> Lunasi
                                                </button>
                                            </form>
                                        @endif

                                        @if($res->status_pemesanan != 'selesai' && $res->status_pemesanan != 'batal')
                                        <form action="{{ route('admin.transaksi.action', $res->id) }}" method="POST" onsubmit="return confirm('Batalkan reservasi ini?');">
                                            @csrf
                                            <input type="hidden" name="action" value="cancel">
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i data-lucide="calendar-off" class="w-10 h-10 text-slate-200"></i>
                                        </div>
                                        <p class="font-bold text-slate-900">Belum ada jadwal reservasi.</p>
                                        <p class="text-sm mt-1">Gunakan dashboard untuk memantau aktivitas menginap.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reservasi->hasPages())
                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
                    {{ $reservasi->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
