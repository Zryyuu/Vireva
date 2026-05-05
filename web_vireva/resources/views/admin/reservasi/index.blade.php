<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Manajemen <span class="text-emerald-600">Reservasi</span></h1>
                <p class="text-sm text-slate-500 font-medium">Pantau jadwal menginap tamu dan kelola status check-in/out.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <form action="{{ route('admin.reservasi.index') }}" method="GET" class="flex gap-2">
                    <select name="month" onchange="this.form.submit()" class="form-select bg-white border border-slate-200 text-slate-700 text-[10px] font-bold rounded-xl px-4 py-2.5 shadow-sm min-w-[110px]">
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $idx => $m)
                            <option value="{{ $idx + 1 }}" {{ $month == ($idx + 1) ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                    <select name="year" onchange="this.form.submit()" class="form-select bg-white border border-slate-200 text-slate-700 text-[10px] font-bold rounded-xl px-4 py-2.5 shadow-sm min-w-[105px]">
                        @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>Tahun {{ $i }}</option>
                        @endfor
                    </select>
                </form>
                <div class="flex gap-1.5 p-1 bg-slate-100 rounded-2xl border border-slate-200/50">
                    <a href="{{ route('admin.reservasi.index', ['status' => 'semua', 'month' => $month, 'year' => $year]) }}" class="px-4 py-2 rounded-xl text-[10px] font-bold transition-all {{ $statusFilter == 'semua' ? 'bg-white text-slate-900 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-700' }}">
                        Semua
                    </a>
                    <a href="{{ route('admin.reservasi.index', ['status' => 'menunggu', 'month' => $month, 'year' => $year]) }}" class="px-4 py-2 rounded-xl text-[10px] font-bold transition-all {{ $statusFilter == 'menunggu' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'text-slate-500 hover:text-slate-700' }}">
                        Menunggu
                    </a>
                    <a href="{{ route('admin.reservasi.index', ['status' => 'aktif', 'month' => $month, 'year' => $year]) }}" class="px-4 py-2 rounded-xl text-[10px] font-bold transition-all {{ $statusFilter == 'aktif' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:text-slate-700' }}">
                        Aktif
                    </a>
                </div>
                <button onclick="document.getElementById('modalManual').classList.remove('hidden')" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    Reservasi Manual
                </button>
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
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500 text-center">Jadwal</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500 text-center">Pembayaran</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500 text-center">Status</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Aksi</th>
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
                                            <div class="text-xs text-slate-400 font-medium tracking-tight">ID: #{{ $res->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-900">{{ $res->villa->nama_villa ?? 'Tidak Diketahui' }}</div>
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Rp {{ number_format($res->total_biaya, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col items-center">
                                        <div class="text-xs font-black text-slate-900">{{ $res->tanggal_checkin->format('d/m/y') }} - {{ $res->tanggal_checkout->format('d/m/y') }}</div>
                                        <div class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full mt-1 border border-emerald-100 uppercase">
                                            {{ $res->total_hari }} Malam
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($res->status_pembayaran == 'settlement')
                                        <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase rounded-full border border-emerald-200">LUNAS</span>
                                    @elseif($res->status_pembayaran == 'pending')
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="px-2 py-1 bg-orange-50 text-orange-600 text-[9px] font-bold uppercase rounded-full border border-orange-200">PENDING</span>
                                            @if($res->bukti_pembayaran)
                                                <button onclick="showBukti('{{ asset('storage/' . $res->bukti_pembayaran) }}', {{ $res->id }})" class="text-[9px] font-bold text-blue-600 hover:underline">LIHAT BUKTI</button>
                                            @endif
                                        </div>
                                    @else
                                        <span class="px-2 py-1 bg-red-50 text-red-600 text-[9px] font-bold uppercase rounded-full border border-red-200">{{ $res->status_pembayaran }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($res->status_pemesanan == 'menunggu')
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded-full">Menunggu</span>
                                    @elseif($res->status_pemesanan == 'aktif')
                                        <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase rounded-full border border-blue-200">Aktif</span>
                                    @elseif($res->status_pemesanan == 'selesai')
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase rounded-full border border-emerald-200">Selesai</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold uppercase rounded-full border border-red-200">Batal</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($res->status_pemesanan == 'menunggu' && $res->status_pembayaran == 'settlement')
                                            <form action="{{ route('admin.transaksi.action', $res->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="checkin">
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-emerald-700">Check-In</button>
                                            </form>
                                        @endif

                                        @if($res->status_pemesanan == 'aktif')
                                            <form action="{{ route('admin.transaksi.action', $res->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="checkout">
                                                <button type="submit" class="px-3 py-1.5 bg-slate-900 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-slate-700">Check-Out</button>
                                            </form>
                                        @endif

                                        @if(in_array($res->status_pemesanan, ['menunggu', 'aktif']))
                                            <form action="{{ route('admin.transaksi.action', $res->id) }}" method="POST" onsubmit="return confirm('Batalkan?');">
                                                @csrf
                                                <input type="hidden" name="action" value="cancel">
                                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-8 py-20 text-center text-slate-400">Belum ada reservasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Manual Booking -->
    <div id="modalManual" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl animate__animated animate__zoomIn">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Input Reservasi Manual</h2>
                    <button onclick="document.getElementById('modalManual').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i data-lucide="x"></i></button>
                </div>
                <form action="{{ route('admin.reservasi.manual') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Nama Tamu</label>
                        <input type="text" name="nama_tamu" required class="w-full rounded-xl border-slate-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">WhatsApp</label>
                        <input type="text" name="no_hape" required class="w-full rounded-xl border-slate-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Pilih Villa</label>
                        <select name="villa_id" required class="w-full rounded-xl border-slate-200 text-sm">
                            @foreach($villas as $villa)
                                <option value="{{ $villa->id }}">{{ $villa->nama_villa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Check-In</label>
                            <input type="date" name="tanggal_checkin" required class="w-full rounded-xl border-slate-200 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Check-Out</label>
                            <input type="date" name="tanggal_checkout" required class="w-full rounded-xl border-slate-200 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Bukti Bayar (Opsional)</label>
                        <input type="file" name="bukti_pembayaran" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>
                    <button type="submit" class="w-full py-3 bg-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all mt-4">Simpan Reservasi</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Bukti Bayar -->
    <div id="modalBukti" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden animate__animated animate__zoomIn">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Verifikasi Pembayaran</h2>
                    <button onclick="document.getElementById('modalBukti').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i data-lucide="x"></i></button>
                </div>
                <img id="imgBukti" src="" class="w-full rounded-2xl mb-6 shadow-sm border border-slate-100">
                <form id="formVerify" action="" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="status" id="inputStatus" value="settlement">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Catatan Admin</label>
                        <textarea name="catatan" class="w-full rounded-xl border-slate-200 text-sm" placeholder="Contoh: Bukti valid, dana masuk..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <button type="submit" onclick="document.getElementById('inputStatus').value='settlement'" class="py-3 bg-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-700">Terima</button>
                        <button type="submit" onclick="document.getElementById('inputStatus').value='cancel'" class="py-3 bg-red-600 text-white rounded-xl font-bold shadow-lg shadow-red-500/20 hover:bg-red-700">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showBukti(url, id) {
            document.getElementById('imgBukti').src = url;
            document.getElementById('formVerify').action = "/admin/reservasi/" + id + "/verify";
            document.getElementById('modalBukti').classList.remove('hidden');
        }
    </script>
</x-admin-layout>
