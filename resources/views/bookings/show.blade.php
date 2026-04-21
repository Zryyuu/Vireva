<x-app-layout>
    <div class="py-12 animate__animated animate__fadeIn">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Reservasi Saya
                </a>
            </div>

            <!-- E-Ticket Main Container -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl border border-slate-200 relative mb-8">
                
                <!-- Ticket Header -->
                <div class="bg-slate-900 px-8 py-8 md:p-10 text-white relative overflow-hidden">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl"></div>
                    <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-emerald-600/20 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-white/10 pb-8 mb-8">
                        <div>
                            <div class="text-[10px] uppercase tracking-widest text-emerald-400 font-bold mb-1">E-Resi & Tiket Menginap</div>
                            <h1 class="text-3xl lg:text-4xl font-black tracking-tight mb-2">#VRV-{{ str_pad($pemesanan->id, 5, '0', STR_PAD_LEFT) }}</h1>
                            <div class="flex items-center gap-2 text-sm text-slate-400">
                                <i data-lucide="clock" class="w-4 h-4"></i> Diterbitkan {{ $pemesanan->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <div class="text-right flex flex-col md:items-end">
                            @if($pemesanan->status_pemesanan == 'aktif')
                                <div class="bg-emerald-500 text-white px-4 py-2 rounded-xl border border-emerald-400 font-bold text-sm tracking-wide shadow-lg shadow-emerald-500/30 inline-flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> LUNAS & AKTIF
                                </div>
                            @elseif($pemesanan->status_pemesanan == 'menunggu')
                                <div class="bg-amber-500 text-white px-4 py-2 rounded-xl border border-amber-400 font-bold text-sm tracking-wide shadow-lg shadow-amber-500/30 inline-flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4"></i> MENUNGGU PEMBAYARAN
                                </div>
                            @elseif($pemesanan->status_pemesanan == 'batal')
                                <div class="bg-red-500 text-white px-4 py-2 rounded-xl border border-red-400 font-bold text-sm tracking-wide shadow-lg shadow-red-500/30 inline-flex items-center gap-2">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> DIBATALKAN
                                </div>
                            @else
                                <div class="bg-slate-700 text-white px-4 py-2 rounded-xl border border-slate-600 font-bold text-sm tracking-wide inline-flex items-center gap-2">
                                    <i data-lucide="check-square" class="w-4 h-4"></i> SELESAI
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Check-In</div>
                            <div class="text-lg font-bold">{{ $pemesanan->tanggal_checkin->format('d M Y') }}</div>
                            <div class="text-xs text-slate-500 mt-1">Mulai 14:00 WIB</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Check-Out</div>
                            <div class="text-lg font-bold">{{ $pemesanan->tanggal_checkout->format('d M Y') }}</div>
                            <div class="text-xs text-slate-500 mt-1">Maks. 12:00 WIB</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Unit Villa</div>
                            <div class="text-lg font-bold">{{ $pemesanan->villa->nama_villa }}</div>
                            <div class="text-[10px] uppercase font-bold text-slate-500 mt-1 truncate tracking-widest">{{ $pemesanan->villa->tipe_villa }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Atas Nama</div>
                            <div class="text-lg font-bold truncate">{{ $pemesanan->tamu->nama_tamu }}</div>
                            <div class="text-xs text-slate-500 mt-1 font-mono text-emerald-400">{{ strtoupper(substr(md5($pemesanan->id), 0, 8)) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Body / Perforated Line Effect -->
                <div class="relative h-8 bg-slate-50">
                    <div class="absolute -left-4 top-0 w-8 h-8 rounded-full bg-slate-50 border-r border-slate-200"></div>
                    <div class="absolute left-8 right-8 top-1/2 border-t-2 border-dashed border-slate-300"></div>
                    <div class="absolute -right-4 top-0 w-8 h-8 rounded-full bg-slate-50 border-l border-slate-200"></div>
                </div>

                <!-- Payment Details -->
                <div class="p-8 md:p-10 bg-slate-50">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6">Rincian Pembayaran</h3>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600 font-medium">Tarif Dasar ({{ $pemesanan->total_hari }} Malam)</span>
                            <span class="text-slate-900 font-bold">{{ $pemesanan->formatted_biaya }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600 font-medium">Pajak Pertambahan Nilai (PPN 0%)</span>
                            <span class="text-slate-900 font-bold">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600 font-medium">Biaya Layanan</span>
                            <span class="text-slate-900 font-bold text-emerald-600">Gratis</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div>
                            <div class="text-xs text-slate-500 font-bold uppercase tracking-widest block mb-1">Total Dibayarkan</div>
                            <div class="text-3xl font-black text-slate-900">{{ $pemesanan->formatted_biaya }}</div>
                        </div>

                        @if($pemesanan->status_pemesanan == 'menunggu')
                            <div class="bg-amber-100 border border-amber-200 rounded-xl p-4 text-sm text-amber-800 font-medium flex items-start gap-3 w-full md:w-auto">
                                <i data-lucide="info" class="w-5 h-5 shrink-0 text-amber-600"></i>
                                <div>Silakan lakukan pembayaran sebesar nilai tagihan di atas ke Front Desk untuk mengaktifkan reservasi Anda.</div>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="w-20 h-20 bg-white border-2 border-slate-900 rounded-lg p-2 mx-auto flex items-center justify-center shadow-sm">
                                    <i data-lucide="qr-code" class="w-12 h-12 text-slate-900"></i>
                                </div>
                                <div class="text-[10px] text-slate-400 font-medium tracking-widest mt-2 uppercase">Scan to Verify</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <button onclick="window.print()" class="px-6 py-3 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl text-slate-700 font-bold shadow-sm transition-colors flex justify-center items-center gap-2">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Tiket
                </button>
                @if($pemesanan->status_pemesanan === 'menunggu')
                    <form action="{{ route('bookings.cancel', $pemesanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?');" class="flex-1 sm:flex-none">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-red-100 hover:bg-red-600 text-red-600 hover:text-white rounded-xl font-bold shadow-sm transition-colors flex justify-center items-center gap-2">
                            <i data-lucide="x-circle" class="w-4 h-4"></i> Batalkan Reservasi
                        </button>
                    </form>
                @endif
            </div>

            <style>
                @media print {
                    nav, button, form, .animate__animated { display: none !important; }
                    body { background: white !important; }
                    .max-w-4xl { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
                    .shadow-xl { shadow: none !important; border: 1px solid #e2e8f0 !important; }
                    .bg-slate-900 { background-color: #0f172a !important; color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                    .bg-emerald-500 { background-color: #10b981 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                }
            </style>
        </div>
    </div>
</x-app-layout>
