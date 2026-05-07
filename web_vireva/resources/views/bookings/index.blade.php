<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold flex items-center gap-2 text-2xl text-slate-900 leading-tight">
            Ruang Kendali <span class="text-emerald-600">Reservasi Saya</span>
        </h2>
    </x-slot>

    <div class="py-12 animate__animated animate__fadeIn">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 p-4 rounded-2xl border-l-4 border-emerald-500 flex items-center gap-3 shadow-sm">
                    <div class="p-2 bg-white rounded-full text-emerald-600 shadow-sm border border-emerald-100">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </div>
                    <div class="text-sm font-bold text-emerald-700">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 bg-red-50 p-4 rounded-2xl border-l-4 border-red-500 flex items-center gap-3 shadow-sm">
                    <div class="p-2 bg-white rounded-full text-red-600 shadow-sm border border-red-100">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    </div>
                    <div class="text-sm font-bold text-red-700">{{ session('error') }}</div>
                </div>
            @endif

            <ul class="flex flex-wrap text-sm gap-2 font-bold text-center text-slate-500 mb-8 border-b border-slate-200 pb-4">
                <li>
                    <a href="#" class="inline-block p-3 text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-xl shadow-sm transition-colors" aria-current="page">Semua Reservasi</a>
                </li>
            </ul>

            @if($bookings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-lg transition-all group flex flex-col relative">
                            
                            <!-- Header Ticket -->
                            <div class="bg-slate-50 border-b border-slate-100 p-6 flex justify-between items-center">
                                <div>
                                    <span class="text-xs font-bold text-slate-400 block mb-0.5 uppercase tracking-widest">No. Reservasi</span>
                                    <span class="text-lg font-black text-slate-900 tracking-tight">#VRV-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="text-right">
                                    @if($booking->status_pemesanan == 'menunggu')
                                        @if($booking->status_pembayaran == 'settlement')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-widest shadow-sm">
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div> Terkonfirmasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold bg-amber-100 text-amber-700 uppercase tracking-widest shadow-sm">
                                                <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div> Menunggu
                                            </span>
                                        @endif
                                    @elseif($booking->status_pemesanan == 'aktif')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-widest shadow-sm">
                                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div> Sedang Menginap
                                        </span>
                                    @elseif($booking->status_pemesanan == 'batal')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold bg-red-100 text-red-700 uppercase tracking-widest shadow-sm">
                                            <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Batal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700 uppercase tracking-widest shadow-sm">
                                            <i data-lucide="check" class="w-3 h-3"></i> Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Body Info -->
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div class="mb-4">
                                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $booking->villa->nama_villa }} <span class="text-xs font-bold uppercase tracking-widest text-slate-500 bg-slate-100 px-2 py-1 rounded-md ml-2">{{ $booking->villa->tipe_villa }}</span></h3>
                                    
                                    <div class="flex items-center gap-3 mt-4 mb-2">
                                        <div class="flex-1">
                                            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1">Check-in</div>
                                            <div class="text-sm font-bold text-slate-800">{{ $booking->tanggal_checkin->format('d M Y') }}</div>
                                        </div>
                                        <div class="text-slate-300">
                                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex-1 text-right">
                                            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1">Check-out</div>
                                            <div class="text-sm font-bold text-slate-800">{{ $booking->tanggal_checkout->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-xs text-center font-bold text-emerald-600 bg-emerald-50 py-1.5 rounded-lg border border-emerald-100 shadow-sm mt-3">{{ $booking->total_hari }} Malam Menginap</div>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="p-6 border-t border-slate-100 border-dashed bg-slate-50 flex items-center justify-between">
                                <div>
                                    <div class="text-[10px] uppercase tracking-widest font-bold text-slate-400 mb-0.5">Total Tagihan</div>
                                    <div class="text-lg font-black text-slate-900">{{ $booking->formatted_biaya }}</div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="p-2.5 bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition-colors shadow-sm" title="Lihat E-Tiket / Resi">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    
                                    @if($booking->status_pemesanan === 'menunggu')
                                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                            @csrf
                                            <button type="submit" class="p-2.5 bg-red-100 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-colors shadow-sm" title="Batalkan Tagihan">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-2xl mx-auto shadow-sm">
                    <div class="w-24 h-24 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="ticket" class="w-12 h-12 text-slate-300"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Belum Ada Reservasi</h3>
                    <p class="text-slate-500 mb-8 max-w-md mx-auto">Anda belum memiliki riwayat reservasi apapun. Eksplorasi keindahan villa mewah Vireva dan rencanakan liburan Anda sekarang.</p>
                    <a href="{{ route('bookings.explore') }}" class="inline-flex py-3 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-md gap-2 items-center">
                        <i data-lucide="search" class="w-5 h-5"></i> Mulai Pencarian Baru
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
